<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Notification\OneSignal\OneSignalService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public $notificationService ;
    public function __construct(

    ){
        $this->notificationService = new OneSignalService();
    }

    public function getAllNotifications(Request $request)
    {
        try{
            $authId = auth()->user()->id ?? 1;
            $notifications = $this->notificationService->getWebNotificationsByUserId($authId);

            $hasUnreadNotification = $this->notificationService->userHasUnreadNotification($authId);

            $data  = array(
                'notifications' => $notifications->items(),
                'meta' => array_merge(
                    pagination_meta($notifications),
                    ['has_unread_notification' => $hasUnreadNotification]
                )
            );

            return ResponseService::apiResponse(200, 'success', $data );


        }catch (\Exception $exception){

            Log::error("NotificationController::getAllNotifications()", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

    }
    public function markUserAllWebNotificationsAsRead()
    {
        try{
            $authId = auth()->user()->id ?? 1;
            $this->notificationService->markUserAllWebNotificationsAsRead($authId);
            return ResponseService::apiResponse(200, 'success' );
        } catch (\Exception $exception){

            Log::error("NotificationController::markUserAllWebNotificationsAsRead()", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

      }

    public function markUserWebNotificationAsRead($notificationId)
    {

        try{
            $authId = auth()->user()->id ?? 1;
            $notification = $this->notificationService->markUserWebNotificationAsRead($authId, $notificationId);
            $hasUnreadNotification = $this->notificationService->userHasUnreadNotification($authId);
            $data = [
                'notification' => $notification,
                'meta' => [
                    'has_unread_notification' => $hasUnreadNotification
                ]
            ];

            return ResponseService::apiResponse(200, 'success', $data );
        } catch (\Exception $exception){

            Log::error("NotificationController::markUserAllWebNotificationsAsRead()", [$exception]);
            return ResponseService::apiResponse(500, "Internal server error");
        }

    }
}
