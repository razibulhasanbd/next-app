<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Helper\Helper;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Services\NewsService;
use App\Services\AccountService;
use Illuminate\Support\Facades\Log;
use App\Services\TradingAccountService;
use Exception;

class CustomerController extends Controller
{
    public function test(NewsService $newsService, AccountService $accountService)
    {


        $news = $newsService->weeksNews(5);

        $newsTradeAccounts = [];

        $account = Account::find(315);

        $accounts = Account::orderBy('id', 'desc')->paginate(400);

        foreach ($accounts as $account) {

            $newsTradeAccounts[] = $accountService->checkNewsTrades($news, $account);
        }
        return  $newsTradeAccounts;
    }

    public function allCustomers(Request $request)
    {

        $validatedData = $request->validate([
            'offset' => 'nullable|integer',
            'count' => 'nullable|integer',

        ]);
        try {

            $users = Customer::all();
            if ($request->has('offset')) {

                $offset = $validatedData['offset'];

                $users = $users->skip($offset)->flatten(1);
            }
            if ($request->has('count')) {

                $count = $validatedData['count'];

                $users = $users->take($count);
            }



            // return $users->toJson();
            return response()->json($users);
        } catch (\Exception $e) {


            throw $e;
        }
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|string|max:255',
                'planId'            => 'required|integer|exists:plans,id',
                'phone'             => 'sometimes|string',
                'city'              => 'sometimes|string',
                'state'             => 'sometimes|string',
                'address'           => 'sometimes|string',
                'zip'               => 'sometimes|string',
                'country'           => 'sometimes|string',
                'country_id'        => 'nullable|integer|exists:countries,id',
                'comment'           => 'sometimes|string',
                'parent_account_id' => 'sometimes|integer',
                'nonConsistent'     => 'sometimes|nullable',
                'server_name'       => 'sometimes|nullable|in:' . AppConstants::TRADING_SERVER_MT4 . ',' . AppConstants::TRADING_SERVER_MT5,
            ]);
            // $validatedData['server_name'] = AppConstants::TRADING_SERVER_MT5;
            $response = (new TradingAccountService)->createAccount($validatedData);
            return response()->json($response);
        } catch (Exception $exception) {
            Log::error("Account create error: ", [$exception, $request->all()]);
            Helper::discordAlert(
                "**Failed To Create Account**:
                       \n*Exception*: " . $exception->getMessage()
                    . "\n*Request*: " . json_encode($request->all())
            );
            return response()->json([
                "message" => "Account creation failed. Please knock to live chat",
            ], 417);
        }
    }



    public function show(int $customer)
    {

        // return $customer;


        $customer = Customer::find($customer);


        if ($customer != null) {

            $customer['accounts'] = $customer->accounts;


            return response()->json($customer);
        } else {
            return response()->json(["message" => "Customer Not Found"], 404); // added error code
        }
    }
    public function testSubEnd()
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
        $exactTime = strtotime('next Friday', $week);

        $mydate = gmdate("Y-m-d H:i:s", $exactTime);

        return [
            'unix' => $exactTime,
            'string' => $mydate,
        ];
    }
}
