<?php

namespace App\Services;

use App\Exports\EvaluationRealExport;
use App\Exports\ExpressDemoExport;
use App\Exports\ExpressRealExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DownloadManagerService
{

    private $module;
    private $data;
    private $downloadExcel;

    /**
     * @param $data
     * @param $module
     * @param $downloadExcel
     */
    public function __construct($data, $module, $downloadExcel)
    {
        $this->data = $data;
        $this->module = $module;
        $this->downloadExcel = $downloadExcel;
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function export() : void
    {

        try{

            $path =  'excel-download/'.$this->downloadExcel->title . ".csv";

            switch ($this->module){
                case 'evaluation_real':
                    Excel::store(new EvaluationRealExport($this->data), $path);
                    break;
                case 'express_real':
                    Excel::store(new ExpressRealExport($this->data),$path);
                    break;
                case 'express_demo':
                    Excel::store(new ExpressDemoExport($this->data), $path);

                    break;
                default:
                    break;
            }

            $contents = Storage::disk('local')->get($path);
            Storage::disk('utility-files')->put($path, $contents, ['visibility' => 'public']);

            // Delete the CSV file from the local storage
            Storage::disk('local')->delete($path);

        }catch (\Exception $exception){

            throw new \Exception($exception->getMessage());
        }

    }
}
