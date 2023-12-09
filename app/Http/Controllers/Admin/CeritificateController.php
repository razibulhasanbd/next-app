<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCeritificateRequest;
use App\Http\Requests\StoreCeritificateRequest;
use App\Http\Requests\UpdateCeritificateRequest;
use App\Models\Ceritificate;
use App\Models\CertificateType;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CeritificateController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('ceritificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Ceritificate::with(['type'])->select(sprintf('%s.*', (new Ceritificate())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ceritificate_show';
                $editGate = 'ceritificate_edit';
                $deleteGate = 'ceritificate_delete';
                $crudRoutePart = 'ceritificates';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('html_markup', function ($row) {
                return $row->html_markup ? $row->html_markup : '';
            });
            $table->addColumn('type_name', function ($row) {
                return $row->type ? $row->type->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'type']);

            return $table->make(true);
        }

        $certificate_types = CertificateType::get();

        return view('admin.ceritificates.index', compact('certificate_types'));
    }

    public function create()
    {
        abort_if(Gate::denies('ceritificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = CertificateType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.ceritificates.create', compact('types'));
    }

    public function store(StoreCeritificateRequest $request)
    {
        $certificate = new Ceritificate();
        if($request->hasFile('demo_image')){
            $fileName = time().'.'.$request->demo_image->extension();
            Storage::disk('utility-files')->putFileAs('Certificates',
                $request->file('demo_image'),$fileName, ['visibility' => 'public']);
            $certificate->demo_image = env('DO_URL').'Certificates/'.$fileName;
        }
        $certificate->name = $request->name;
        $certificate->html_markup = $request->html_markup;
        $certificate->type_id = $request->type_id;
        $certificate->save();

        return redirect()->route('admin.ceritificates.index');
    }

    public function edit(Ceritificate $ceritificate)
    {
        abort_if(Gate::denies('ceritificate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = CertificateType::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $ceritificate->load('type');

        return view('admin.ceritificates.edit', compact('ceritificate', 'types'));
    }

    public function update(UpdateCeritificateRequest $request, Ceritificate $ceritificate)
    {
        $array = [
            'name' => $request->name,
            'html_markup' => $request->html_markup,
            'type_id' => $request->type_id,
        ];
        if($request->hasFile('demo_image')){
            $fileName = time().'.'.$request->demo_image->extension();
            Storage::disk('utility-files')->putFileAs('Certificates',
                $request->file('demo_image'),$fileName, ['visibility' => 'public']);
            $array['demo_image'] = env('DO_URL').'Certificates/'.$fileName;
        }
        $ceritificate->update($array);
        return redirect()->route('admin.ceritificates.index');
    }

    public function show(Ceritificate $ceritificate)
    {
        abort_if(Gate::denies('ceritificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ceritificate->load('type', 'certificateAccountCertificates');

        return view('admin.ceritificates.show', compact('ceritificate'));
    }

    public function destroy(Ceritificate $ceritificate)
    {
        abort_if(Gate::denies('ceritificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ceritificate->delete();

        return back();
    }

    public function massDestroy(MassDestroyCeritificateRequest $request)
    {
        Ceritificate::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
