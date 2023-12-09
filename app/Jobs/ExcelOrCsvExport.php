<?php

namespace App\Jobs;

use App\Exports\ExpressRealExport;
use App\Models\Account;
use App\Models\DownloadManager;
use App\Repository\DownloadManagerRepository;
use App\Services\DownloadManagerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ExcelOrCsvExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    private $request;
    private $downloadExcelId;
    private $userId;
    private $module;
    private $userType;
    private $applicationType;
    private $userReportingTo;
    public $downloadManagerRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $downloadExcelId, $module, $user)
    {
        $this->request = $request;
        $this->downloadExcelId = $downloadExcelId;
        $this->userId = $user->id;
        $this->module = $module;
        $this->downloadManagerRepository = new DownloadManagerRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '-1');
            ini_set("pcre.backtrack_limit", "5000000");

            Log::debug($this->module . ' csv generate Start ...');
            $data = array();

            if (in_array($this->module, [ 'evaluation_real', 'express_real', 'express_demo'])) {
                $data = $this->downloadManagerRepository->accountDataByPlanType($this->module);
            }

            $downloadExcel = DownloadManager::where('id', $this->downloadExcelId)->first();
            if ($downloadExcel) {
                if (sizeof($data) > 0) {
                    (new DownloadManagerService($data, $this->module, $downloadExcel))->export();
                    $downloadExcel->status = 1;
                    $downloadExcel->save();
                    Log::debug($this->module . ' csv generate End ...');
                } else {
                    $downloadExcel->status = 2;
                    $downloadExcel->remark = 'No data found';
                    $downloadExcel->save();
                }
            } else {
                Log::debug("DownloadManager file not found/didn't generate!");
            }


        } catch (\Exception $exception) {
            Log::error('ExcelOrCsvExport::handle()' . $this->module . '-' . $exception->getMessage());
            Log::error($exception);
            $downloadExcel = DownloadManager::where('id', $this->downloadExcelId)->first();
            if ($downloadExcel) { // If exists...
                $downloadExcel->status = 2;
                $downloadExcel->remark = $exception->getMessage();
                $downloadExcel->save();
            }

        }
    }


}
