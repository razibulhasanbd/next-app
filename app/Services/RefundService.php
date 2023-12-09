<?php
namespace App\Services;

use App\Models\RefundRequest;
use Illuminate\Support\Facades\Auth;

class RefundService
{

    /**
     * Store Refund Request
     *
     * @param integer $order_id
     * @param float $amount
     * @param integer $status
     * @param string $comment
     * @param [type] $reply_comment
     * @return RefundRequest
     */
    public function storeRefund(int $order_id, float $amount, int $status, string $comment, $reply_comment): RefundRequest
    {
        $refundRequest = new RefundRequest();
        $refundRequest->order_id = $order_id;
        $refundRequest->user_id = Auth::user()->id;
        $refundRequest->amount = $amount;
        $refundRequest->status = $status;
        $refundRequest->comment = $comment;
        $refundRequest->reply_comment = $reply_comment;
        $refundRequest->save();
        return $refundRequest;
    }


    /**
     * Update Refund Request
     *
     * @param integer $order_id
     * @param integer $status
     * @param string $reply_comment
     * @return void
     */
    public function updateRefundRequest(int $order_id, int $status, string $reply_comment)
    {
        $refundInfo = RefundRequest::where('status', RefundRequest::STATUS_PENDING)->where('order_id',$order_id)->first();
        if (!$refundInfo) return ResponseService::apiResponse(200, 'This request not match our database!', ['status' => false]);
        $refundInfo->update(['status'=> $status, 'reply_comment' => $reply_comment]);
        return ResponseService::apiResponse(200, 'Refund Update Successfully', ['status' => true]);
    }
}