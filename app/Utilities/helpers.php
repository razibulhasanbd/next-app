<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\PhaseService;
use App\Services\SettingService;
use Illuminate\Support\Optional;
use App\Services\Plan\PlanService;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Contracts\Auth\Authenticatable;


if (!function_exists('api')) {
    /**
     * @param $data
     * @return \App\Utilities\ApiJsonResponse
     */
    function api($data = []): \App\Utilities\ApiJsonResponse
    {
        return new \App\Utilities\ApiJsonResponse($data);
    }
}


if (!function_exists('toaster')) {
    /**
     * @return \App\Utilities\ToasterAlert|null
     */
    function toaster(): ?\App\Utilities\ToasterAlert
    {
        if (is_null(\App\Utilities\ToasterAlert::$instance)) {
            \App\Utilities\ToasterAlert::$instance = new \App\Utilities\ToasterAlert();
        }

        //dd(\App\Utilities\ToasterAlert::$instance);

        return \App\Utilities\ToasterAlert::$instance;
    }
}


if (!function_exists('auth_check')) {
    function auth_check(): bool
    {
        return auth()->check();
    }
}

if (!function_exists('currency_usd_to_cent')) {
    function currency_usd_to_cent(float $amountInUsd): int
    {
        return (int)($amountInUsd * 100);
    }
}

if (!function_exists('currency_cent_to_usd')) {
    function currency_cent_to_usd(int $amountInCent): int|float
    {
        return $amountInCent / 100;
    }
}

if (!function_exists('remove_empty_fields')) {
    function remove_empty_fields(array $fields): array
    {
        return array_filter($fields, function ($val) {
            return $val != null;
        });
    }
}

if (!function_exists('get_image_prefix_directory_name')) {
    function get_image_prefix_directory_name(): string
    {
        return Carbon::today()->format('m_Y');
    }
}

if (!function_exists('get_media_prefix_directory_name')) {
    function get_media_prefix_directory_name(): string
    {
        return Carbon::today()->format('m_Y') . '_media';
    }
}

if (!function_exists('default_date_time_format')) {
    function default_date_time_format(): string
    {
        return 'Y-m-d h:m:s';
    }
}

if (!function_exists('default_date_format')) {
    function default_date_format(): string
    {
        return 'Y-m-d';
    }
}

if (!function_exists('settings')) {
    function settings(?string $key = null): mixed
    {
        $setting = app(SettingService::class);

        if (is_null($key)) {
            return $setting->getAll();
        }

        return $setting->get($key);
    }
}

if (!function_exists('get_query_str_from_url')) {
    function get_query_params_from_url($url)
    {
        return parse_url($url, PHP_URL_QUERY);
    }
}



if (!function_exists('carbon')) {
    function carbon($date = null)
    {
        if (!$date) {
            return Carbon::now(config('app.timezone', 'UTC'));
        }

        return (new Carbon($date, config('app.timezone', 'UTC')));
    }
}

if (!function_exists('to_sentence')) {
    function to_sentence($sentence)
    {
        return  ucwords(str_replace(['__', '_'], ' ', \Illuminate\Support\Str::snake($sentence)));
    }
}

if (!function_exists('all_caps_to_ucfirst')) {
    function all_caps_to_ucfirst($sentence)
    {
        return Str::ucfirst(Str::lower($sentence));
    }
}

if (!function_exists('pagination_meta')) {
    function pagination_meta(AbstractPaginator $paginator): array
    {
        return [
            'total' => $paginator->count(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'has_more' => $paginator->hasMorePages(),
        ];
    }
}

if (!function_exists('profit_settings')) {
    function profit_settings($plan = null, $key = null): mixed
    {
        $setting = app(PlanService::class);

        $planData = $setting->getProfit($plan,$key);
        return $planData;
    }
}

if (!function_exists('phase_profit_settings')) {
    function phase_profit_settings($plan = null, $key = null): mixed
    {
        $setting = app(PhaseService::class);

        $planData = $setting->getProfit($plan,$key);
        return $planData;
    }
}

if (!function_exists('get_php_runtime_type')) {
    function get_php_runtime_type($cli = 'CLI', $web = 'Web'): string
    {
        if( defined('STDIN') )
        {
            return $cli;
        }

        if( empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0)
        {
            return $cli;
        }

        return $web;
    }
}

function showErrorPublic($param, $msg = 'Sorry! Something went wrong! '): string
{
    $j = strpos($param, '(SQL:');
    if ($j > 15) {
        $param = substr($param, 8, $j - 9);
    }
    return $msg . $param;
}
