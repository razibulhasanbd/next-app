<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:analytics');
    }

    public function index(Request $request)
    {
        try
        {
            // Checking about the permissions of specifically daily reports.
            abort_if(Gate::denies('analytics_daily_reports'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            // Validating the request.
            $request->validate([
                'from_date' =>'nullable|date',
                'to_date' =>'required_with:from_date|nullable|date|after:from_date',
            ]);

            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            // Getting daily reports data from the analytics service.
            $getDailyReportData = new AnalyticsService();
            $getDailyReportData = $getDailyReportData->dailyReport($request);

            // Returning data from the analytics service
            return view('admin.Analytics.index', compact('fromDate', 'toDate', 'getDailyReportData'));
        }
        catch (\Exception $e)
        {
            Log::error("Error in daily report in AnalyticsController -> index, ".$e->getMessage());
            return back();
        }
    }
}
