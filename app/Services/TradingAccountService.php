<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Plan;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\AccountMetric;
use App\Constants\AppConstants;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use App\Constants\TradingAccountGroupConstant;

class TradingAccountService
{

    protected $password      = null;
    protected $parentAccount = null;

    public function createAccount(array $customerData): array
    {
        try {
            Log::info("customer data", [$customerData]);
            if (isset($customerData['parent_account_id']) && $customerData['parent_account_id'] != null){
                if( $this->duplicateAccountExists($customerData['parent_account_id'])) {
                    Log::error('duplicate account creation try', [$customerData]);
                    throw new Exception("duplicate account creation try");
                }
            }

            $customer        = $this->createCustomer($customerData);
            $plan            = $this->getPlanInfo($customerData['planId'], isset($customerData['server_name']) ? $customerData['server_name'] : AppConstants::TRADING_SERVER_MT4);
            $startingBalance = $this->getStartingBalance($customerData, $plan);
            $login           = $this->createMt4Account($customerData, $plan, $startingBalance);  // create mt4 account (http call)
            // $this->deposit($account, $plan, $startingBalance); // deposit to the account (http call)

            DB::beginTransaction();

            $account      = $this->customerAccountCreate($customer, $plan, $login, isset($customerData['parent_account_id']) ? $customerData['parent_account_id'] : null, $startingBalance);
            $subscription = $this->createSubscription($account, $plan);
            $this->createMetric($account, Carbon::now(), $startingBalance);
            $this->createMetric($account, Carbon::yesterday(), $startingBalance);
            $this->accountRuleCreate($customerData, $account, $plan);
            $this->allowNewsTrade($customerData, $account, $plan);

            if($plan->type == Plan::EV_REAL || $plan->type == Plan::EX_REAL )
            {
                $this->realAccountEmail($customer->name, $customer->email, str_replace('Real', '', $plan->type), $login, $this->password, $plan->server->friendly_name, $this->parentAccount->login);
            }
            elseif($plan->type == Plan::EV_P2 && isset($this->parentAccount->login) && $this->parentAccount->login != "")
            {
                $this->p2AccountEmail($customer->name, $customer->email, $login, $this->password, $plan->server->friendly_name, $this->parentAccount->login);
            }

            DB::commit();

            Helper::discordAlert(
                "**New Account Created **:\nAccntID : " . $account->id
                    . "\nBalance: " .  $startingBalance
                    . "\nName: " .  $customer->name
                    . "\nEmail: " .  $customer->email
            );

            if($customer->tags != 0 || $customer->tags != null){

                //Abuser || suspected customer alert
                Helper::discordAlert(
                "**" . Customer::TAGS[$account->customer->tags] . "Customer" . "**:
                **New Account Created From" . " " . Customer::TAGS[$customer->tags] . "Customer" . " " . "**:\nAccntID : " . $account->id
                    . "\nBalance: " .  $startingBalance
                    . "\nName: " .  $customer->name
                    . "\nEmail: " .  $customer->email
                    ,true
                );
            }


            return [
                'customerId'     => $customer->id,
                'accountId'      => $account->id,
                'password'       => $account->password,
                'serverId'       => $plan->server->id,
                'login'          => $account->login,
                'planId'         => $plan->id,
                'subscrptionEnd' => $subscription->ending_at,
            ];
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }


    private function accountPassword()
    {
        if (!$this->password) {
            $this->password = (new PasswordService)->passwordGenerate();
        }
    }


    /**
     * create customer function
     *
     * @param array $customerData
     * @return Customer
     */
    public function createCustomer(array $customerData): Customer
    {
        $customer = Customer::where('email', $customerData['email'])->first();
        if ($customer) return $customer;

        $this->accountPassword();
        return Customer::create([
            'name'       => $customerData['name'],
            'email'      => $customerData['email'],
            'password'   => $this->password,
            'phone'      => $customerData['phone'] ?? null,
            'city'       => $customerData['city'] ?? null,
            'state'      => $customerData['state'] ?? null,
            'address'    => $customerData['address'] ??  null,
            'zip'        => $customerData['zip'] ?? null,
            'country'    => $customerData['country'] ?? null,
            'country_id' => $customerData['country_id'] ?? null,
        ]);

        throw new Exception("Customer create failed error");
    }


    /**
     * get Plan Information function
     *
     * @param int $planId
     * @param string $serverName
     * @return Plan
     */
    public function getPlanInfo(int $planId, string $serverName = AppConstants::TRADING_SERVER_MT4): Plan
    {
        Log::info("server name", [$serverName]);
        if($serverName == AppConstants::TRADING_SERVER_MT5){
            Log::info("server name 11", [$serverName]);
            $plan = Plan::with('mt5server')->find($planId);
            $plan->server = $plan->mt5server;
            return $plan;
        }
        else{
            Log::info("server name 22", [$serverName]);
            return Plan::with('server')->find($planId);
        }
    }


    /**
     * Create mt4 Account on Manager
     *
     * @param array $customerData
     * @param Plan|null $plan
     * @param float $startingBalance
     * @return integer
     * @throws Exception
     */
    public function createMt4Account(array $customerData, Plan $plan = null, float $startingBalance): int
    {
        if (!$plan)
            $plan = $this->getPlanInfo($customerData['planId'], isset($customerData['server_name']) ? $customerData['server_name'] : AppConstants::TRADING_SERVER_MT4);

        $this->accountPassword();
        $response = Http::timeout(12)->acceptJson()->post($plan->server->url . "/user/add?token=" . $plan->server->login, [
            'login'                  => 0,
            'name'                   => $customerData['name'] . " FundedNext",
            'group'                  => $this->getGroupName($plan, $customerData['country_id'] ?? null, isset($customerData['server_name']) ? $customerData['server_name'] : AppConstants::TRADING_SERVER_MT4),
            'email'                  => $customerData['email'],
            'password'               => $this->password,
            'phone'                  => $customerData['phone'] ?? null,
            'status'                 => 'status',
            'state'                  => $customerData['state'] ?? null,
            'city'                   => $customerData['city'] ?? null,
            'country'                => $this->getCountryName($customerData['country_id'] ?? null),
            'address'                => $customerData['address'] ?? null,
            'comment'                => 'comment',
            'agent_account'          => 0,
            'id_number'              => '0',
            'leverage'               => 100,
            'enabled'                => 1,
            'enable_change_password' => 0,
            'send_report'            => 0,
            'read_only'              => 0,
            'deposit_balance'        => $startingBalance
        ]);

        Log::info("Response", [$response]);

        if (($response['code'] == 200) && isset($response['user']['login'])) {
            return $response['user']['login'];
        }

        Log::error("Account create error response from mt4 ------- ", [$response]);
        throw new Exception("Account create error");
    }


    /**
     * create account metric
     *
     * @param Account $account
     * @param Carbon $date
     * @param float $startingBalance
     * @return void
     */
    public function createMetric(Account $account, Carbon $date, float $startingBalance): void
    {
        AccountMetric::create([
            "account_id"          => $account->id,
            "maxDailyLoss"        => 0,
            "maxMonthlyLoss"      => 0,
            "metricDate"          => $date,
            "isActiveTradingDay"  => false,
            "trades"              => 0,
            "averageLosingTrade"  => 0,
            "averageWinningTrade" => 0,
            "lastBalance"         => $startingBalance,
            "lastEquity"          => $startingBalance,
            "lastRisk"            => 0,
        ]);
    }


    /**
     * deposit to account
     *
     * @param Account $account
     * @param Plan $plan
     * @param float $balance
     * @return Response
     */
    public function deposit(Account $account, Plan $plan, float $balance): Response
    {
        return Http::acceptJson()->post($plan->server->url . "/user/deposit?token=" . $plan->server->login, [
            'login'     => $account->login,
            'is_credit' => false,
            'amount'    => $balance,
            'comment'   => 'Deposit'
        ]);
    }


    /**
     * create subscription
     *
     * @param Account $account
     * @param Plan $planInfo
     * @return Subscription
     */
    public function createSubscription(Account $account, Plan $plan): Subscription
    {
        $subend = Helper::subend_days($plan->duration);
        return Subscription::create([
            'account_id' => $account->id,
            'login'      => $account->login,
            'plan_id'    => $plan->id,
            'ending_at'  => $subend['string'],
        ]);
    }


    /**
     * account create in our server
     *
     * @param Customer $customer
     * @param Plan $plan
     * @param integer $login
     * @param integer|null $parentId
     * @param float $startingBalance
     * @return Account
     */
    public function customerAccountCreate(Customer $customer, Plan $plan, int $login, int $parentId = null, float $startingBalance): Account
    {
        $account                      = new Account();
        $account->customer_id         = $customer->id;
        $account->login               = $login;
        $account->password            = Crypt::encrypt($this->password);
        $account->type                = $plan->title;
        $account->plan_id             = $plan->id;
        $account->name                = $customer->name;
        $account->balance             = $startingBalance;
        $account->equity              = $startingBalance;
        $account->trading_server_type = $plan->server->trading_server_type;
        $account->parent_account_id   = $parentId;
        $account->starting_balance    = $startingBalance;
        $account->server_id           = $plan->server->id;
        $account->duration            = $plan->duration;
        $account->save();

        return $account;
    }


    /**
     * account rule create
     *
     * @param array $customerData
     * @param Account $account
     * @param Plan $plan
     * @return void
     */
    public function accountRuleCreate(array $customerData, Account $account, Plan $plan): void
    {
        if ((isset($customerData['nonConsistent']) && $customerData['nonConsistent'] == 1)) {
            if (Plan::EX_DEMO == $plan->type || Plan::EX_REAL == $plan->type) {
                $accountRuesService = new AccountRulesService($account);
                $accountRuesService->ncaAccountRuleCreate();
            }
        }
        elseif(Plan::EX_REAL == $plan->type){
            $this->setParentAccount($customerData['parent_account_id']);
            $planRules = $this->parentAccount->planRules();
            if (isset($planRules['NCA'])) {
                $accountRuesService = new AccountRulesService($account);
                $accountRuesService->ncaAccountRuleCreate();
            }
        }
    }


    /**
     * allow news trade in account rule
     *
     * @param array $customerData
     * @param Account $account
     * @param Plan $plan
     * @return void
     */
    public function allowNewsTrade(array $customerData, Account $account, Plan $plan): void
    {

        if(Plan::EX_DEMO == $plan->type || Plan::EX_REAL == $plan->type) return;

        if (Plan::EV_P1 == $plan->type) {
            // $accountRuesService = new AccountRulesService($account);
            // $accountRuesService->allowNewsTradeRuleCreate();
        } elseif (Plan::EV_P2 == $plan->type) {
            $this->setParentAccount($customerData['parent_account_id']);
            $this->parentAccount = Account::find($customerData['parent_account_id']);
            $getPlanRules        = $this->parentAccount->planRules();
            if (isset($getPlanRules['ANT'])) {
                $accountRuesService = new AccountRulesService($account);
                $accountRuesService->allowNewsTradeRuleCreate();
            }
        } elseif (Plan::EV_REAL == $plan->type) {
            $this->parentAccount = Account::find($customerData['parent_account_id']);
            $getPlanRules        = $this->parentAccount->planRules();
            if (isset($getPlanRules['ANT'])) {
                $accountRuesService = new AccountRulesService($account);
                $accountRuesService->allowNewsTradeRuleCreate();
            }
        }
    }


    /**
     * need get balance
     *
     * @param array $customerData
     * @param Plan $plan
     * @return float
     */
    public function getStartingBalance(array $customerData, Plan $plan): float
    {
        if (isset($customerData['parent_account_id'])) {
            $this->setParentAccount($customerData['parent_account_id']);
            if ($this->parentAccount && Plan::EX_DEMO == $this->parentAccount->plan->type) {
                $planRules = $this->parentAccount->planRules();
                if (isset($planRules['NCA'])) {
                    return  $this->parentAccount->starting_balance * AppConstants::NCA_STARTING_BALANCE_FOR_REAL_ACCOUNT;
                }
            }
        }
        return $plan->startingBalance;
    }


    /**
     * set parent account model to $parentAccount variable
     *
     * @param integer|null $parentAccountId
     * @return void
     */
    public function setParentAccount(int $parentAccountId) : void{
        if(!$this->parentAccount)
            $this->parentAccount = Account::with('plan')->find($parentAccountId);
    }


    /**
     * get Group Name
     *
     * @param Plan $plan
     * @param integer|null $countryId
     * @param string $serverName
     * @return string
     */
    public function getGroupName(Plan $plan, $countryId, $serverName = AppConstants::TRADING_SERVER_MT4): string
    {
        return $plan->server->group;
        if($serverName == AppConstants::TRADING_SERVER_MT4){
            if ((Plan::EV_REAL == $plan->type || Plan::EX_REAL  == $plan->type) && ($plan->startingBalance == "15000" || $plan->startingBalance == "25000" || $plan->startingBalance == "50000")) {
                return TradingAccountGroupConstant::REAL_ACCOUNT_FOR_15_25_50_k_GROUP;
            }

            if ((Plan::EV_REAL == $plan->type || Plan::EX_REAL  == $plan->type) && ($plan->startingBalance == "100000" || $plan->startingBalance == "200000")) {
                return TradingAccountGroupConstant::REAL_ACCOUNT_FOR_100_200_k_GROUP;
            }

            if (Plan::EV_REAL == $plan->type || Plan::EX_REAL == $plan->type) {
                return TradingAccountGroupConstant::REAL_ACCOUNT_GROUP;
            }

            if ($countryId && in_array($countryId, [170, 132, 204]) && $plan->server_id == 4) {
                return TradingAccountGroupConstant::VIETNAM_TRADERS_GROUP;
            }
        }
        return $plan->server->group;
    }


    /**
     * get country name from country id
     *
     * @param integer|null $countryId
     * @return string
     */
    public function getCountryName(int $countryId = null) : string{
        if($countryId){
            return Country::find($countryId)->name;
        }
        return "";
    }


    /**
     * duplicate account check
     *
     * @param integer|null $parentAccountId
     * @return bool
     */
    public function duplicateAccountExists(int $parentAccountId = null){
        if($parentAccountId){
            $accounts = Account::where('parent_account_id', $parentAccountId)->whereBreached('0')->where('breachedby', null)->count();
            if ($accounts) return true;
        }
        return false;
    }


    /**
     * Send customer email for p1 to p2 migration
     *
     * @param string $firsName,
     * @param string $email,
     * @param int $login,
     * @param string $password,
     * @param string $server
     * @return void
     */
    private function p2AccountEmail(string $firstName, string $email, int $login, string $password, string $server, int $parent_account_id): void
    {
        $details = [
            'template_id'          => EmailConstants::PHASE1_PHASE2_MIGRATION,
            'to_name'              => Helper::getOnlyCustomerName($firstName),
            'to_email'             => $email,
            'email_body' => [
                "name" => Helper::getOnlyCustomerName($firstName),
                "mt4_login_id"=>$login,
                "mt4_login_password"=>$password,
                "mt4_server_id"=>$server,
                "parent_login_id" => $parent_account_id,
                "trustpilot_url" => EmailConstants::TRUSTPILOT_URL
                ]
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }


    /**
     * Send customer email for real acocunt
     *
     * @param string $firsName,
     * @param string $email,
     * @param string $planName,
     * @param int $login,
     * @param string $password,
     * @param string $server
     * @return void
     */
    private function realAccountEmail(string $firstName, string $email, string $plan_title, int $login, string $password, string $server, int $parent_login_id): void
    {

        $details = [
            'template_id'          => EmailConstants::REAL_ACCOUNT_RECEIVED,
            'to_name'              => Helper::getOnlyCustomerName($firstName),
            'to_email'             => $email,
            'email_body' => [
                "name" => Helper::getOnlyCustomerName($firstName),
                "mt4_login_id"=>$login,
                "mt4_login_password"=>$password,
                "mt4_server_id"=>$server,
                "plan_title"=>$plan_title,
                "parent_login_id"=>$parent_login_id,
                "trustpilot_url" => EmailConstants::TRUSTPILOT_URL
                ]
        ];
        EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }


}
