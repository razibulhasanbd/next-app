<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExcelOrCsvExport;
use App\Models\DownloadManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadManagerController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('download_manager'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['page']       = $this->getPage();
        $query              = DownloadManager::orderBy('id', 'desc');

        if(!Auth::user()->isAdmin) {
            $query              = $query->where('user_id',auth()->user()->id);
        }
        if($request->filled('module')){
            $query->where('module', $request->module);
        }
        if($request->filled('status')){
            $query->where('status', $request->status);
        }

        if ($request->filled('date_to') && $request->filled('date_from')) {

            $date = setStartDateEndDate($request->date_from, $request->date_to, 'Y-m-d');

            $query->whereDate('created_at', '>=', $date['from'])->whereDate('created_at', '<=', $date['to']);
        }
        $data['csv_list'] =  ($request->pagination ? $query->paginate($request->pagination) : $query->paginate(50));
        return view('admin.downloadManager.index',$data);
    }
    public function generateCSV(Request $request) {

        if($request->filled('download')){
            $module             = $request->download;
        } else {
            $module             = 'download_manager';
        }
        $fileName                   = $module.'_csv_' . time();

        $path =  'excel-download/'.$fileName . ".csv";
        $downloadLink = Storage::disk('excel-download')->url($path);
        $downloadExcel              = new DownloadManager();

        $downloadExcel->module      = $module;
        $downloadExcel->title       = $fileName;
        $downloadExcel->url         = $downloadLink;
        $downloadExcel->user_id     = auth()->user()->id;
        $downloadExcel->save();

        Log::debug("Job set for new download");
        $job = (new ExcelOrCsvExport($request->all(), $downloadExcel->id,$module, auth()->user()));

        dispatch($job);

        return redirect()->route('admin.download-manager.index');
    }
    public function generatedCSVDelete($id) {
        try {
            $csv = DownloadManager::findOrFail($id);

            if ($csv->url) {
                Storage::disk('excel-download')->delete('excel-download/'.$csv->title . ".csv");
            }
            $csv->delete();

           // toastr()->success('Delete Successfully.', langapp('response_status'));
            return redirect()->route('admin.download-manager.index');
        } catch (\Exception $exception){
            Log::error($exception);
          //  toastr()->error('Something went wrong', langapp('error'));
            return redirect()->route('admin.download-manager.index');
        }
    }



    private function getPage() {
        return "Download Manager";
    }


}
