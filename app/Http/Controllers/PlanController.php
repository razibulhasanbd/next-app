<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountRuleTemplate;
use App\Models\Plan;
use App\Models\TargetReachedAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class PlanController extends Controller
{
    //

    public function getPlanId($id)
    {
        $approval_category_info = TargetReachedAccount::with('approval_category')->where('id',$id)->first();
        
        $account = Account::find($approval_category_info->account_id);
        $template = AccountRuleTemplate::with('rule_name')->where('plan_id', $account->plan_id)->get();
        $planRules = $account->planRules();

        //Redis Connection for account current balance
        $server = $account->server;
        $url = $server->url;
        $sessionToken = $server->login;
        $redisData = json_decode(Redis::get('margin:' . $account->login), 1);
        if ($redisData != null) {
            $telescope[] = "Redis available";
            $currentBalance = $redisData['balance'];
        } else {

            $redisDataFromApi = Http::get($url . "/user/margin/" . $account->login . "?token=" . $sessionToken);
            $redisData = json_decode($redisDataFromApi, 1);
            $currentBalance = $redisData['balance'];
        }
        $growthFundAmount = 0;
        if (isset($planRules['AGF'])) {
            // ! If account has GrowthFund add that also to profit amount

            $growthFunds = $account->growthFund;
            if ($growthFunds != null) {

                $growthFundAmount = $growthFunds->sum('amount');
            }
        }

        $profit = $currentBalance - $account->starting_balance;
        $withdrawableAmount = $profit * ($planRules['PS']['value']) / 100;

        
        $getValue = [
            'account_id' => $account->id,
            'profit' => $profit,
            'approval_category' => $approval_category_info->approval_category->name,
            'withdrawableAmount' => $withdrawableAmount,
            'growthFundAmount' => $growthFundAmount,
            'template' => $template,
        ];

        return response()->json($getValue);
    }

    public function show(int $plan)
    {
        $plan = Plan::without('server')->findOrFail($plan);

        $profit_target = $plan->planRule->where('ruleName.condition', '=', 'PT')->first();
        $withdrawable_profit = $plan->planRule->where('ruleName.condition', '=', 'WPP')->first();
        $plan['profit_target'] = $profit_target ? $profit_target['value'] : null;
        $plan['withdrawable_profit_percentage'] = $withdrawable_profit ? $withdrawable_profit['value'] : null;

        return response()->json($plan);
    }
    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $validatedData = $request->validate([
                'type' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'upgradePlanId' => 'sometimes|integer',
                'accountMaxDrawdown' => 'required',
                'accountProfitTarget' => 'required',
                'startingBalance' => 'required',
                'dailyLossLimit' => 'required',
                'upgradeThreshold' => 'required',
                'accumulatedProfit' => 'required',
                'profitShare' => 'required',
            ]);
        } else {

            return "POST required";
        }

        try {
            $plan = Plan::create([
                'type' => $validatedData['type'],
                'title' => $validatedData['title'],
                'upgradePlanId' => (isset($validatedData['upgradePlanId']) ? $validatedData['upgradePlanId'] : 0),
                'description' => $validatedData['description'],

                'accountMaxDrawdown' => $validatedData['accountMaxDrawdown'],
                'accountProfitTarget' => $validatedData['accountProfitTarget'],
                'startingBalance' => $validatedData['startingBalance'],
                'dailyLossLimit' => $validatedData['dailyLossLimit'],
                'upgradeThreshold' => $validatedData['upgradeThreshold'],
                'accumulatedProfit' => $validatedData['accumulatedProfit'],
                'profitShare' => $validatedData['profitShare'],
                // 'username' => $validatedData['username'],
            ]);

            return response()->json($plan, 201);
        } catch (\Exception$e) {

            throw $e;
        }
    }

    public function allPlans(Request $request)
    {
        $validatedData = $request->validate([
            'offset' => 'sometimes|integer',
            'count' => 'sometimes|integer',

        ]);
        try {
            $plans = Plan::all();
            $plans->makeHidden(['server']);

            foreach ($plans as $plan) {

                $profit_target = $plan->planRule->where('ruleName.condition', '=', 'PT')->first();
                $withdrawable_profit = $plan->planRule->where('ruleName.condition', '=', 'WPP')->first();
                $plan['profit_target'] = $profit_target ? $profit_target['value'] : null;
                $plan['withdrawable_profit_percentage'] = $withdrawable_profit ? $withdrawable_profit['value'] : null;
            }
            if ($request->has('offset')) {

                $offset = $validatedData['offset'];
                $plans = $plans->skip($offset)->flatten(1);
            }
            if ($request->has('count')) {

                $count = $validatedData['count'];
                $plans = $plans->take($count);
            }

            // return $plans->toJson();
            return response()->json($plans);
        } catch (\Exception$e) {

            throw $e;
        }
    }
}
