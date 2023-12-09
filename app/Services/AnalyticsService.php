<?php

namespace App\Services;


use App\Models\Orders;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnalyticsService
{

    /**
     * basic response
     *
     * @param object $request
     */
    public function dailyReport($request)
    {
        try
        {
            // Defining dates if requested dates are empty.
            $fromDate = date('Y-m-d 00:00:01', strtotime('-30 days'));
            $toDate = date('Y-m-d 23:59:59');

            // If the from date is not empty than parsing and formatting as SQL
            if (!empty($request->from_date))
            {
                $fromDate = Carbon::parse($request->from_date)->toDateString()." 00:00:01";
            }

            // If the to date is not empty than parsing and formatting as SQL
            if (!empty($request->to_date))
            {
                $toDate = Carbon::parse($request->to_date)->toDateString()." 23:59:59";
            }

            // Query that returns required properties of sales reports.
            $orders = Orders::select(DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN order_type = 1 THEN grand_total ELSE 0 END) as `new_sale_revenue`'),
                DB::raw('COUNT(CASE WHEN order_type = 1 THEN grand_total ELSE NULL END) as `new_sale_count`'),
                DB::raw('SUM(CASE WHEN order_type = 2 THEN grand_total ELSE 0 END) as `top_up_revenue`'),
                DB::raw('COUNT(CASE WHEN order_type = 2 THEN grand_total ELSE NULL END) as `top_up_count`'),
                DB::raw('SUM(CASE WHEN order_type = 3 THEN grand_total ELSE 0 END) as `reset_revenue`'),
                DB::raw('COUNT(CASE WHEN order_type = 3 THEN grand_total ELSE NULL END) as `reset_count`')
            )
            ->where('status', Orders::STATUS_ENABLE)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'), 'desc')
            ->paginate(Orders::ANALYTICS_DATA_LIMITS);

            return $orders;
        }
        catch (\Exception $e)
        {
            Log::error("Error in AnalyticsService -> dailyReport, ".$e->getMessage());
            return [];
        }
    }
}
