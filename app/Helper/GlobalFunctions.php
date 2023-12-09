<?php

use App\Constants\AppConstants;
use App\Models\Account;
use App\Models\Customer;
use App\Models\JlUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

function frontEndTimeConverterView($dateTime, $type = 'date-time'): ?string
{
    return $dateTime ?? "";
    $timezone  = new DateTimeZone('Etc/GMT-6'); // GMT+6:00
    $timestamp = strtotime($dateTime);
    if ($timestamp === false) {
        $datetime = new DateTime(str_replace('.', '-', $dateTime));
    } else {
        $datetime = new DateTime($dateTime);
    }
    $datetime->setTimezone($timezone);
    return $type == 'date' ? $datetime->format("Y-m-d") : $datetime->format("Y-m-d H:i:s");
}

function paymentGateways(): array
{
    $paymentGatways = [
        1 => "Checkout",
        2 => "Outside",
        3 => "Stripe",
        4 => "Free Account",
    ];
    return $paymentGatways;
}

function statusArray(): array
{
    $status = [
        0 => "Disabled",
        1 => "Enabled",
        2 => "Pending"
    ];
    return $status;
}


function downloadManagerModules(): array
{
    return [
        'express_real' => "Express Real",
        'express_demo' => "Express Demo",
        'evaluation_real' => "Evaluation Real"
    ];
}

function downloadManagerStatus(): array
{
    return [
        '0' => "Pending",
        '1' => "Completed",
        '2' => "Failed",
    ];
}
function setStartDateEndDate($start_date, $end_date, $format = "Y-m-d H:i:s") : array
{

    if ($start_date) {
        $date = new \DateTime($start_date);
        $start_date = $date->format($format);
    }
    if ($end_date) {
        $date = new \DateTime($end_date);
        $end_date = $date->format($format);
    }

    if ((isset($start_date) && $start_date != null) && (isset($end_date) && $end_date != null)) {
        if ($start_date > $end_date) {
            $tempDate = $start_date;
            $start_date = $end_date;
            $end_date = $tempDate;
        }
    } else if ((isset($start_date) && $start_date != null) && (isset($end_date) && $end_date == null)) {
        $start_date = $start_date;
        $end_date = $start_date;
    } else {
        $start_date = $end_date;
        $end_date = $end_date;
    }

    return ['from' => $start_date, 'to' => $end_date];
}
function generateUniqueId(): ?string
{
    $last6DigitUnique = substr(uniqid(), -5);
    $bytes            = random_bytes(4);
    return bin2hex($bytes) . $last6DigitUnique;
}

function getFirstNameLastNameFromText($name){
    $exp        = explode(' ', $name);
    $first_name = '';
    $last_name  = '';
    if (count($exp) == 2) {
        $first_name = $exp[0];
        $last_name  = $exp[1];
    } elseif (count($exp) > 2) {
        $first_name = $exp[0] . ' ' . $exp[1];
        $last       = $exp[3] ?? "";
        $last_name  = $exp[2] . ' ' . $last;
    }
    return [
        'first_name' => $first_name,
        'last_name'  => $last_name,
    ];
}

function refundStatusArray(): array
{
    $status = [
        0 => "Pending",
        1 => "Approved",
        2 => "Rejected"
    ];
    return $status;
}


/**
 * Retrieves authenticated JL customer data from Redis cache.
 *
 * @param bool $reload Whether to force a reload of the data from the database and update the cache.
 *
 * @return stdClass|null Returns the authenticated JL customer data as a stdClass object on success,
 *                       or null if no data is found or an error occurs.
 *
 * @throws Exception If Redis connection fails or an error occurs while fetching data from cache,
 *                    or if an exception is caught while executing the function.
 */
function getAuthJlCustomer(bool $reload = false){
    try {
        $userId = (Redis::connection('token'))->get(request()->bearerToken());
        if (!Redis::GET("temp-user-data-$userId") || $reload) {
            Redis::SETEX("temp-user-data-$userId", 1800, JlUser::find($userId));
        }
        return json_decode(Redis::GET("temp-user-data-$userId"));
    }
    catch(Exception $exception){
        Log::error("Auth error ", ["Exception" => $exception , "Request " => request()]);
        throw new Exception("Internal server error. Check log file");
    }
}


/**
 * Retrieves authenticated customer data from Redis cache based on the authenticated JL customer email.
 *
 * @param bool $reload Whether to force a reload of the data from the database and update the cache.
 *
 * @return stdClass|null Returns the authenticated customer data as a stdClass object on success,
 *                       or null if no data is found or an error occurs.
 *
 * @throws Exception If an error occurs while fetching data from cache,
 *                    or if an exception is caught while executing the function.
 */
function getAuthCustomer(bool $reload = false){
    try {
        $jlUser = getAuthJlCustomer();
        if (!Redis::GET("temp-customer-data-$jlUser->email") || $reload) {
            Redis::SETEX("temp-customer-data-$jlUser->email", 1800, Customer::whereEmail($jlUser->email)->first());
        }
        return json_decode(Redis::GET("temp-customer-data-$jlUser->email"));
    } catch (Exception $exception) {
        Log::error("Auth error ", ["Exception" => $exception, "Request " => request()]);
        throw new Exception("Internal server error. Check log file");
    }
}


/**
 * Retrieves an Account model instance based on the provided account ID and authenticated customer data.
 *
 * @param int $accountId The ID of the account to retrieve.
 *
 * @return Account|null Returns the Account model instance on success, or null if no matching account is found.
 *
 */
function getAuthenticateAccount(int $accountId) : ?Account{
    $customer = getAuthCustomer();
    return Account::where('customer_id', $customer->id)->where('id', $accountId)->first();
}

function weekName()
{
    return [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday'
    ];
}


/**
 * Returns an array of available payment methods with their respective names.
 *
 * @return array An associative array where the keys are payment method codes and the values are payment method names.
 */
function paymentMethodList(): array
{
    return [
        'crypto'        => "Crypto",
        'bank_transfer' => "Bank Transfer",
        'doge'          => "DOGE",
        'ltc'           => "LTC",
        'usdc'          => "USDC(ERC20)",
        'usdt-trc20'    => "USDT(TRC20)",
        'usdt-erc20'    => "USDT(ERC20)",
        'solana'        => "SOL",
        'perfect-money' => "Perfect Money",
        'bank-transfer' => "Bank Transfer",
        'wmz-purse'     => "WMZ-purse (WebMoney)",
        'usdc-trc20'    => "USDC(TRC20)",
    ];
}


/**
 * Returns an array of available payment method types with their respective names, or the name of a specific payment method type if specified.
 *
 * @param string $type (optional) The payment method type code to retrieve the name for.
 * @return array|string An associative array where the keys are payment method type codes and the values are payment method type names, or the name of the specified payment method type.
 */
function paymentMethodFormType($type = '')
{
    $types = [
        'crypto' => "Crypto and Web Money",
        'bank_transfer' => "Bank Transfer",
        'perfect_money' => "Perfect Money",
    ];
    if($type){
        return $types[$type] ?? '';
    }
    return $types;
}

if (!function_exists('pagination_meta')) {
    function pagination_meta(\Illuminate\Pagination\AbstractPaginator $paginator): array
    {
        return [
            'total' => $paginator->count(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'has_more' => $paginator->hasMorePages(),
        ];
    }
}

function getTradingServer()
{
    return [
        AppConstants::TRADING_SERVER_MT4 => 'MT4',
        AppConstants::TRADING_SERVER_MT5 => 'MT5'
    ];
}

