## About Backend Api Collection

Run the following commands

php artisan migrate  
php artisan db:seed  
php artisan sentry:publish --dsn=https://35f39f2382984f498bf349f6315c6854@o1142070.ingest.sentry.io/6200924

*   [Api Collection](https://www.getpostman.com/collections/1e699869d1773458e13c).

---

> ## **Laravel HORIZON Work Flow**
> 
> ## Type 1
> 
> #### If the job is normal and you can handle it with the default value then
> 
> ```plaintext
> When you are dispaching the job, add ->onQueue(AppConstants::QUEUE_DEFAULT_JOB)
> ```
> 
> ## Type 2
> 
> #### If the job is highly valuable, then please follow the steps below. Remember all #### will be replaced by your value.
> 
> Go to `app/Constants/AppConstants.php`
> 
> Add a const job name under the //Queues sections. The variable name must be uppercase & the value must be lowercase.
> 
> Go to `.env` & add the following values
> 
> ```php
>   SUPERVISOR_QUEUE_####_JOB_ENABLED=true
>   SUPERVISOR_QUEUE_####_JOB_MIN_PROCESS=1
>   SUPERVISOR_QUEUE_####_JOB_MAX_PROCESS=3
> ```
> 
> Copy the above three values and paste them to `.env.example`
> 
> Go to `config/const.php` & add the following values
> 
> ```php
>   'SUPERVISOR_QUEUE_####_JOB_ENABLED' => env('SUPERVISOR_QUEUE_####_JOB_ENABLED', true),
>   'SUPERVISOR_QUEUE_####_JOB_MIN_PROCESS' => env('SUPERVISOR_QUEUE_####_JOB_MIN_PROCESS', 1),
>   'SUPERVISOR_QUEUE_####_JOB_MAX_PROCESS' => env('SUPERVISOR_QUEUE_####_JOB_MAX_PROCESS', 3),
> ```
> 
> *   Go to `app/Helper/HorizonHelpers/HorizonDevelopmentSupervisor.php`  `app/Helper/HorizonHelpers/HorizonLocalSupervisor.php`  `app/Helper/HorizonHelpers/HorizonProductionSupervisor.php` and add the below code block to all _**get()**_ functions. Feel free to change your config variable values.
> 
> ```php
>   if (config('const.SUPERVISOR_QUEUE_####_JOB_ENABLED')) {
>     $supervisors['supervisor_'.AppConstants::QUEUE_####_JOB] = [
>         'connection' => 'redis',
>         'queue' => [
>             AppConstants::QUEUE_####_JOB,
>         ],
>         'balance'      => 'auto',
>         'minProcesses' => config('const.SUPERVISOR_QUEUE_####_JOB_MIN_PROCESS', 1),
>         'maxProcesses' => config('const.SUPERVISOR_QUEUE_####_JOB_MAX_PROCESS', 3),
>         'tries'        => AppConstants::JOB_TRIES,
>         'memory'       => 1000,
>         'timeout'      => 600                                                                //10 minutes
>     ];
> }
> ```
> 
> The abode code `'connection'` value can be changeable. If your job is a normal running job, then make it the value `redis` or if it is a long-running job, then please make it `redis-long-running`
> 
> Go to `config/horizon.php` & search for `wait` keyword. Then add the following element to it. Remember the element `key` will depend on your `abode configuration`
> 
> ```php
>   'redis:'. AppConstants:: QUEUE_####_JOB => 600,
>     or
>   'redis-long-running:'. AppConstants:: QUEUE_####_JOB => 600,
> ```
> 
> run the below command
> 
> Open your browser and go to [http://127.0.0.1:8090/horizon/dashboard](http://127.0.0.1:8090/horizon/dashboard) . Ask the team for the username and password
> 
> ```php
> php artisan horizon:terminate
> php artisan horizon:pause
> ```
> 
> ```php
> Example: public const QUEUE_####_JOB = '####';
> ```

---

> ## **Account Status Work Flow**
> 
> ```php
> // Import the below class
> use App\Services\AccountStatusLogService;
> // check app/Models/AccountStatus.php and app/Models/AccountStatusMessage.php const values
> // for creating a new log. Use your own values:
> AccountStatusLogService::create(Account $account, int $newStatus, int $message = null, mixed $data = nul);
> 
>             
> ```

---

---

> ## _**Custom API Auth**_
> 
>  **custom\_client\_auth** :  This middleware needs the bearer token of a user from the JoulesLabs backend. We will use it in API.FUN
> 
> Read [Technical Documentation to authenticate JL users in API.Fun](https://next-ventures-tech-team.notion.site/Technical-Documentation-to-authenticate-JL-users-in-API-Fun-ba4aec6518404b53b2aa23c2694b8ff1)
> 
> ## _Usages_
> 
> ### **1\. getAuthJlCustomer**
> 
> This function retrieves authenticated JL (presumably a company or brand) customer data from a Redis cache based on the bearer token provided in the request headers.
> 
> ### **2\. getAuthCustomer**
> 
> This function retrieves authenticated customer data from a Redis cache based on the authenticated JL customer email.
> 
> ### **3\. getAuthenticateAccount**
> 
> This function retrieves an **Account** model instance based on the provided account ID and authenticated customer data.
> 
> ```php
> function getAuthJlCustomer(bool $reload = false);
> function getAuthCustomer(bool $reload = false);
> function getAuthenticateAccount(int $accountId): ?Account
> ```

---
