<?php

namespace App\Services\Notification\OneSignal;


use App\Jobs\OneSignalNotificationSendingJob;
use App\Models\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Log;


class OneSignalService extends OneSignalBase
{
    /**
     * @param $message
     * @param $userIds
     * @param $url
     * @param $data
     * @param $buttons
     * @param $schedule
     * @param $headings
     * @param $subtitle
     * @return string[]
     */

    public static function sendNotification($message, $userIds = null, $url = null, $data = null, $buttons = null, $schedule = null, $headings = null, $subtitle = null)
    {

        OneSignalNotificationSendingJob::dispatch(message:$message,userIds:$userIds , url:$url , data:$data, buttons:$buttons, schedule:$schedule, headings:$headings , subtitle:$subtitle );
    }
    /**
     * Send a notification with custom parameters defined in
     * https://documentation.onesignal.com/reference#section-example-code-create-notification
     * @param array $parameters
     * @return mixed
     */
    public function sendNotificationCustom($parameters = []){
        try{

            if (isset($parameters['api_key'])) {
                $this->headers['headers']['Authorization'] = 'Basic '.$parameters['api_key'];
            }

            // Make sure to use app_id
            if (!isset($parameters['app_id'])) {
                $parameters['app_id'] = $this->appId;
            }
            // Make sure to use included_segments
            if (empty($parameters['included_segments']) && empty($parameters['include_player_ids']) && empty('include_external_user_ids')) {
                $parameters['included_segments'] = ['All'];
            }

            $parameters = array_merge($parameters, $this->additionalParams);

            $this->headers['body'] = json_encode($parameters);
            $this->headers['buttons'] = json_encode($parameters);
            $this->headers['verify'] = false;

            Log::info(json_encode($parameters));
            $response  = $this->post(self::ENDPOINT_NOTIFICATIONS);
            Log::info(json_encode($response));
            return json_decode($response->getBody()->getContents() ?? []);

        }catch (Exception $exception){

            Log::error("OneSignalService::sendNotificationCustom()" . $exception->getMessage());
            throw new Exception("Couldn't get response from onesignal");
        }

    }

    /**
     * @param $message
     * @param $userId
     * @param null $url
     * @param null $data
     * @param null $buttons
     * @param null $schedule
     * @param null $headings
     * @param null $subtitle
     */
    public function sendNotificationToExternalUser($message, $userIds, $url = null, $data = null, $buttons = null, $schedule = null, $headings = null, $subtitle = null) {
        try {
            $contents = array(
                "en" => $message
            );


            $params = array(
                'app_id' => $this->appId,
                'contents' => $contents,
                'include_external_user_ids' => is_array($userIds) ? $userIds : array($userIds)
            );

            if (isset($url)) {
                $params['url'] = $url;
            }

            if (isset($data)) {
                $params['data'] = $data;
            }

            if (isset($buttons)) {
                $params['buttons'] = $buttons;
            }

            if(isset($schedule)){
                $params['send_after'] = $schedule;
            }

            if(isset($headings)){
                $params['headings'] = array(
                    "en" => $headings
                );
            }

            if(isset($subtitle)){
                $params['subtitle'] = array(
                    "en" => $subtitle
                );
            }

            $response  = $this->sendNotificationCustom($params);
            return $this->successResponse(data:$response);

        }catch (Exception $exception){
            Log::error("OneSignalService::sendNotificationToExternalUser()" . $exception->getMessage());
            return $this->errorResponse();
        }

    }



    /**
     * @param $message
     * @param $url
     * @param $data
     * @param $buttons
     * @param $schedule
     * @param $headings
     * @param $subtitle
     * @return string[]
     */
    public function sendNotificationToAll($message, $url = null, $data = null, $buttons = null, $schedule = null, $headings = null, $subtitle = null) {
        try{
            $contents = array(
                "en" => $message
            );

            $params = array(
                'app_id' => $this->appId,
                'contents' => $contents,
                'included_segments' => array('All')
            );

            if (isset($url)) {
                $params['url'] = $url;
            }

            if (isset($data)) {
                $params['data'] = $data;
            }

            if (isset($buttons)) {
                $params['buttons'] = $buttons;
            }

            if(isset($schedule)){
                $params['send_after'] = $schedule;
            }

            if(isset($headings)){
                $params['headings'] = array(
                    "en" => $headings
                );
            }

            if(isset($subtitle)){
                $params['subtitle'] = array(
                    "en" => $subtitle
                );
            }
            Log::info(json_encode($params));
            $response  = $this->sendNotificationCustom($params);
            Log::info(json_encode($response));
            return $this->successResponse(data:$response);

        }catch (Exception $exception){
            Log::error("OneSignalService::sendNotificationToAll()" . $exception->getMessage());
            return $this->errorResponse();
        }

    }


    public function post($endPoint) {
        if($this->requestAsync === true) {
            $promise = $this->client->postAsync($this->apiUrl . $endPoint, $this->headers);
            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }
        return $this->client->post($this->apiUrl . $endPoint, $this->headers);
    }

    public function put($endPoint) {
        if($this->requestAsync === true) {
            $promise = $this->client->putAsync($this->apiUrl . $endPoint, $this->headers);
            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }
        return $this->client->put($this->apiUrl . $endPoint, $this->headers);
    }

    public function get($endPoint) {
        return $this->client->get($this->apiUrl . $endPoint, $this->headers);
    }

    public function delete($endPoint) {
        if($this->requestAsync === true) {
            $promise = $this->client->deleteAsync($this->apiUrl . $endPoint, $this->headers);
            return (is_callable($this->requestCallback) ? $promise->then($this->requestCallback) : $promise);
        }
        return $this->client->delete($this->apiUrl . $endPoint, $this->headers);
    }

    public function storePushNotificationByBatch($batch, $message)
    {
       try{
           // Create an array of push notification data for all users in the batch
           $push_notifications = array_map(function ($user_id) use ($message) {
               return [
                   'type' => "App\Models\User",
                   'notifiable_type' => 'user',
                   'notifiable_id' => $user_id,
                   'data' => $message,
                   'status' => 'delivered',
                   'created_at' => Carbon::now(),
                   'updated_at' => Carbon::now()
               ];
           }, $batch);

           // Insert all push notification data for this batch into the database using a batch insert
           Notification::insert($push_notifications);
       }catch (Exception $exception)
       {
           Log::error("OneSignalService::storePushNotificationByBatch()",[$exception]);
       }
    }
    public function getWebNotificationsByUserId(int $userId): AbstractPaginator
    {
        return Notification::where('notifiable_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->paginate(request('per_page'));
    }

    public function userHasUnreadNotification(int $userId): bool
    {
        return Notification::where('notifiable_id', $userId)
            ->where('read_at', null)
            ->exists();
    }

    public function markUserAllWebNotificationsAsRead(int $userId): bool
    {
        return (bool) Notification::where('notifiable_id', $userId)->update([
            'read_at' => Carbon::now()
        ]);
    }

    public function markUserWebNotificationAsRead(int $userId, string $notificationId): Notification
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_id', $userId)
            ->first();

        if (!$notification) {
            throw new \NotFoundException;
        }

        $notification->update([
            'read_at' => Carbon::now()
        ]);

        return $notification->refresh();
    }
}
