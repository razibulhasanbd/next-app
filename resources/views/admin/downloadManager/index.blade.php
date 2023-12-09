@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
      Download Manager : Total-{{ $csv_list->toArray()['total'] }}
        <a href="{{ route('admin.download-manager.index')}}" class="btn btn-success btn-sm pull-right">Refresh List</a>

    </div>

    <section class="panel panel-default">

        <form action="{{ route('admin.download-manager.index')}}" method="GET">
            <div class="row card-body">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="module">Module</label>
                        <select class="form-control" name="module" id="module">
                            <option value="">Select Module</option>
                            @foreach(downloadManagerModules() as $key => $module)
                                <option {{ request('module') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $module }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Select Status</option>
                            @foreach(downloadManagerStatus() as $key => $value)
                                <option {{ request('status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="startDate">From Date</label>
                        <input class="form-control datetime" value="{{ request('date_from') }}" type="text" name="date_from" id="startDate">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="endDate">To Date</label>
                        <input class="form-control datetime" value="{{ request('date_to') }}" type="text" name="date_to" id="endDate">
                    </div>
                </div>
                <div class="col-md-2 mt-4">
                    <button type="submit" class="btn btn-success" style="padding: 6px 19px;"><i class="fa fa-search"></i></button>
                    <a href="{{ route('admin.download-manager.index')}}" class="btn btn-danger">Clear</a>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped" id="clients-table">
                <thead>
                <tr>
                    <td style="width: 5%">SL</td>
                    <td style="width: 15%">Module</td>
                    <td style="width: 20%">Title</td>
                    <td style="width: 8%">Status</td>
                    <td style="width: 20%">Remarks</td>
                    <td style="width: 10%">Created At</td>
                    <td style="width: 15%">Action</td>
                </tr>
                </thead>
                <tbody>
                @if($csv_list->count())
                        <?php $i = $csv_list->toArray()['from'];  ?>
                    @foreach($csv_list as $csv)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{ ucwords( str_replace("_"," ",$csv->module))}}</td>
                            <td>{{ ucfirst($csv->title) }}</td>
                            <td>
                                @if($csv->status == 1)
                                    <span class="badge badge-success">Completed</span>
                                @elseif($csv->status == 2)
                                    <span class="badge badge-danger">Failed</span>
                                @else Processing... <div class="lds-ellipsis">  <div></div><div></div><div></div><div></div></div>
                                @endif
                            </td>
                            <td>{!! $csv->remark ?? '' !!}</td>
                            <td>{{$csv->created_at ?? ''}}</td>
                            <td>

                                <form action="{{ route('admin.download-manager.generated-file-delete', $csv->id) }}" method="POST">
                                    @method('DELETE')
                                    @can("download_manager_download_generated_csv")
                                    @if($csv->status == 1)

                                        @if($csv->url)
                                            <a  class="btn btn-info btn-sm"  href="{!!   $csv->url !!}">Download</a>
                                        @else
                                            File Not Found
                                        @endif
                                    @endif
                                @endcan
                                @can("download_manager_delete_generated_csv")
                                    <button class="btn btn-danger btn-sm" title="Delete" type="submit" onclick="return confirm('Are you sure you want to delete this item?');"> <i class="fa-fw fas fa-trash"></i></button>
                                @endcan
                                </form>
                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>

        </div>
        {{ $csv_list->withQueryString()->links('pagination::bootstrap-4') }}
    </section>
</div>

<style>
    .lds-ellipsis {
        display: inline-block;
        position: relative;
        width: 50px;
        height: 10px;
    }
    .lds-ellipsis div {
        position: absolute;
        /*top: 20px;*/
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #3498db;
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }
    .lds-ellipsis div:nth-child(1) {
        left: 8px;
        animation: lds-ellipsis1 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(2) {
        left: 8px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(3) {
        left: 32px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(4) {
        left: 56px;
        animation: lds-ellipsis3 0.6s infinite;
    }
    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }
    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }
    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(24px, 0);
        }
    }
</style>

@endsection
@section('scripts')
@parent
<script>

</script>
@endsection
