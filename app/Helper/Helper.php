<?php

namespace App\Helper;

use App\Constants\AppConstants;
use App\Jobs\DiscordAlertJob;
use App\Services\ResponseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class Helper
{

    public static function mt4init($getPlanServer)
    {
        try {
            $response = Http::timeout(15)->get($getPlanServer->url . "/init/", [
                'server' => $getPlanServer->server,
                'login' => $getPlanServer->login,
                'password' => $getPlanServer->password,
            ]);

            if ($response->successful()) {
                return ResponseService::basicResponse(200, "", [],$response['token']);
            } else {
                return ResponseService::basicResponse(500, "MT4 Server timeout");
            }
        } catch (Throwable) {}
    }

    public static function ping($getPlanServer)
    {

        try {
            $response = Http::timeout(15)->get($getPlanServer->url . "/ping/", [
                'token' => $getPlanServer->login,
            ]);

            if ($response->successful()) {

                return "Ok";
            } else {
                Helper::discordAlert("**Fail Ping Server**:\nServerUrl : " . $getPlanServer->url);

                // return ['error' => 'MT4 Server timeout'];
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            abort(500, $e);
        }
    }
    public static function subendDaysFrom($days, $from)
    {
        $from=strtotime($from);
        $now = $from;
        $nowDay = gmdate('w', $now);
        $nowTime = gmdate('H-i', $now);

        switch ($nowDay) {
            case 0:
                $addWeek = (int) (($days / 7) - 1);
                break;
            case 1:
                // echo "Monday";g
                if ($nowTime >= 21) {
                    // echo "9tar beshi baaje\n";
                    // echo "\n";
                    $addWeek = (int) ($days / 7);
                } else {
                    $addWeek = (int) (($days / 7) - 1);
                }
                break;
            case 2:

                $addWeek = (int) ($days / 7);


                // echo "Tuesday";
                break;
            case 3:
                $addWeek = (int) ($days / 7);
                // echo "Wednesday";
                break;
            case 4:
                $addWeek = (int) ($days / 7);
                // echo "Thursday";
                break;
            case 5:
                $addWeek = (int) ($days / 7) - 1;
                // echo "Friday";
                break;
            case 6:
                $addWeek = (int) ($days / 7) - 1;
                // echo "Saturday";
                break;
            default:
                return "Day out of range";
        }

        $week = strtotime('+' . $addWeek . ' weeks', $now);
        $exactTime = strtotime('next Friday 11:59 PM', $week);

        $mydate = date("Y-m-d H:i:s", $exactTime);

        return [
            'unix' => $exactTime,
            'string' => $mydate,
        ];


    }
    public static function subend_days($days)
    {

        $now = time();
        $nowDay = gmdate('w', $now);
        $nowTime = gmdate('H-i', $now);

        switch ($nowDay) {
            case 0:
                $addWeek = (int) (($days / 7) - 1);
                break;
            case 1:
                // echo "Monday";g
                if ($nowTime >= 21) {
                    // echo "9tar beshi baaje\n";
                    // echo "\n";
                    $addWeek = (int) ($days / 7);
                } else {
                    $addWeek = (int) (($days / 7) - 1);
                }
                break;
            case 2:

                $addWeek = (int) ($days / 7);


                // echo "Tuesday";
                break;
            case 3:
                $addWeek = (int) ($days / 7);
                // echo "Wednesday";
                break;
            case 4:
                $addWeek = (int) ($days / 7);
                // echo "Thursday";
                break;
            case 5:
                $addWeek = (int) ($days / 7) - 1;
                // echo "Friday";
                break;
            case 6:
                $addWeek = (int) ($days / 7) - 1;
                // echo "Saturday";
                break;
            default:
                return "Day out of range";
        }

        $week = strtotime('+' . $addWeek . ' weeks', $now);
        $exactTime = strtotime('next Friday 11:59 PM', $week);

        $mydate = date("Y-m-d H:i:s", $exactTime);

        return [
            'unix' => $exactTime,
            'string' => $mydate,
        ];
    }

    public static function subend()
    {

        $now = time();
        $nowDay = gmdate('w', $now);
        $nowTime = gmdate('H-i', $now);

        switch ($nowDay) {
            case 0:
                // echo "Sunday";
                $addWeek = 3;
                break;
            case 1:
                // echo "Monday";
                $addWeek = 3;
                break;
            case 2:
                if ($nowTime >= 21) {
                    // echo "9tar beshi baaje\n";
                    // echo "\n";
                    $addWeek = 4;
                } else {
                    $addWeek = 3;
                }

                // echo "Tuesday";
                break;
            case 3:
                $addWeek = 4;
                // echo "Wednesday";
                break;
            case 4:
                $addWeek = 4;
                // echo "Thursday";
                break;
            case 5:
                $addWeek = 3;
                // echo "Friday";
                break;
            case 6:
                $addWeek = 3;
                // echo "Saturday";
                break;
            default:
                return "Day out of range";
        }

        $week = strtotime('+' . $addWeek . ' weeks', $now);
        $exactTime = strtotime('next Friday 11:59 PM', $week);

        $mydate = date("Y-m-d H:i:s", $exactTime);

        return [
            'unix' => $exactTime,
            'string' => $mydate,
        ];
    }

    // for  Trade pagination
    public static  function  getTradePage($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
    }
    // for pagination end

    public static function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
    }

    public static function discordAlert($message,$customerTagAlert = false)
    {
        DiscordAlertJob::dispatch($message,$customerTagAlert)->onQueue(AppConstants::QUEUE_DISCORD_ALERT_JOB);
    }

    public static function getOnlyCustomerName(string $name) : string{
        return str_ireplace('FundedNext', '', $name);
    }

    /** remove faq cache
     * @return void
     */
    public static function forgetFaqCache()
    {
        Cache::forget('get_faq');
        Cache::forget('get_faq_without_type');
        Cache::forget('get_faq_without_tag');
        Cache::forget('get_faq_others');
        Cache::forget('faq_output');
    }



}
