<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\AppConstants;
use Exception;
use App\Models\UtilityItem;
use App\Models\UtilityCategory;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class UtilityApiController extends Controller
{
    /**
     * return category
     *
     * @return \Illuminate\Http\Response
     */
    public function getUtilityCategories()
    {
        try {
            $utilityCategories = UtilityCategory::select('id', 'name')->where('status', 1)->orderBy('order_value', 'asc')->get();
            if (!$utilityCategories) {
                return  ResponseService::apiResponse(404, 'No category data found');
            }

            return  ResponseService::apiResponse(
                200,
                'Utility categories retrieved',
                [
                    'utility_categories' => $utilityCategories
                ]
            );
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }


    /**
     * return utility
     *
     * @return \Illuminate\Http\Response
     */
    public function getUtilityItems()
    {

        try {
            $utilityItems = Cache::remember(AppConstants::CACHE_KEY_UTILITY_ITEMS, 3600, function () {
                $utilityItems = UtilityItem::where('status', 1)->orderBy('order_value', 'asc')->get()->groupBy('utility_category_id')->groupBy(function ($item, $key) {

                    return UtilityCategory::find($key)->name;
                });
                return $utilityItems;
            });

            if (!$utilityItems) {
                return  ResponseService::apiResponse(404, 'No items data found');
            }
            return  ResponseService::apiResponse(
                200,
                'Utility items retrieved',
                [
                    'utility_items' => $utilityItems
                ]
            );
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }


    /**
     * return both utility and category
     *
     * @return \Illuminate\Http\Response
     */
    public function getUtilityAndCategory()
    {
        try {
            $utilityItems = Cache::remember(AppConstants::CACHE_KEY_UTILITY_ITEMS, 3600, function () {
                $utilityItems = UtilityItem::where('status', 1)->orderBy('order_value', 'asc')->get()->groupBy('utility_category_id');
                return $utilityItems;
            });

            $utilityCategories = Cache::remember(AppConstants::CACHE_KEY_UTILITY_CATEGORIES, 3600, function () {
                return UtilityCategory::select('id', 'name')->where('status', 1)->orderBy('order_value', 'asc')->get();
            });

            return  ResponseService::apiResponse(
                200,
                'Utility items and category retrieved',
                [
                    'utility_items'      => $utilityItems,
                    'utility_categories' => $utilityCategories
                ]
            );
        } catch (Exception $exception) {
            Log::error($exception);
            return  ResponseService::apiResponse(500, 'Internal server error');
        }
    }
}
