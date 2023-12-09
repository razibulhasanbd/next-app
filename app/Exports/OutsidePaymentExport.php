<?php

namespace App\Exports;

use App\Models\Typeform;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OutsidePaymentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      $typeForm = Typeform::whereNull('archived_at')->whereBetween('created_at', [request()->startDate, request()->endDate])->get();

      $typeForm->each(function($item){
        unset($item->deleted_at);
        unset($item->updated_at);


        if($item->payment_verification == 1){
            $item->payment_verification = "Pending";
        }elseif($item->payment_verification == 2){
            $item->payment_verification = "Verified";
        }elseif($item->payment_verification == 3){
            $item->payment_verification = "Not Verified";
        }else{
            $item->payment_verification = "Duplicate";
        }
        
        return $item;

    });
    return $typeForm;

    }
    public function headings(): array
    {
        return [
            'Id',
            'Payments For',
            'Funding Package',
            'Funding Amount',
            'Coupon Code',
            'Payment Method',
            'Payment Proof',
            'Paid Amount',
            'Name',
            'Email',
            'Country',
            'Login',
            'Payment Verification',
            'Approved At',
            'Transaction Id',
            'Denied At',
            'Remarks',
            'Archived At',
            'Referred By',
            'Created By',
            'Plan Id'
        ];
    }
}