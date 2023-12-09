<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountCertificate;
use App\Models\Ceritificate;
use App\Models\CertificateType;
use App\Services\CertificateService;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;
use Imagick;

class CertificateController extends Controller
{
    /**
     * certificate details
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function getCertificates(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login' => 'required|int|exists:accounts,login',
            ]);

            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            $certificateData = CertificateService::certificateInfo($request->login);
            $CertificateType = CertificateType::whereNull("deleted_at")->pluck('name')->toArray();
            $certificate     = Ceritificate::with('type')->whereNull("deleted_at")->get();

            $certificateArray = [];
            $certificateTypes = [];
            $unlocked         = [];
            foreach ($certificateData as $item) {
                $certificateArray[]               = [
                    'accountCertificateId' => $item->id,
                    'docId'                => $item->doc_id,
                    'certificateData'      => $item->certificate_data,
                    'certName'             => $item->certificate->name,
                    'category'             => $item->certificate->type->name,
                    'certTypeId'           => $item->certificate->type_id,
                    'certId'               => $item->certificate->id,
                    'htmlMarkup'           => $item->certificate->html_markup,
                    'createdAt'            => $item->created_at,
                    'url'                  => $item->url,
                    'unlocked'             => true,
                    'share'                => $item->share,
                ];
                $unlocked[$item->certificate->id] = $item->certificate->id;
            }
            foreach ($certificate as $item) {
                $certificateTypes [] = $item->name;
                if (in_array($item->id, $unlocked)) {
                    continue;
                }
                $certificateArray[] = [
                    'certId'     => $item->id,
                    'certName'   => $item->name,
                    'category'   => $item->type->name,
                    'htmlMarkup' => $item->html_markup,
                    'url'        => $item->demo_image,
                    'unlocked'   => false
                ];
            }

            return ResponseService::apiResponse(200,
                'You are eligible', [
                    'status'          => true,
                    'certificateData' => [
                        'certificates'          => $certificateArray,
                        'certificateType'       => $certificateTypes,
                        'certificateCategories' => $CertificateType
                    ]
                ]);

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    /**
     * Get trading info
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function getTradingData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login'      => 'required|int|exists:accounts,login',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date'   => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }

            $data            = [];
            $certificateData = CertificateService::dateWiseTradingInfo($request);
            foreach ($certificateData as $item) {
                $data['dates'][]    = $item->metricDate;
                $data['balances'][] = $item->lastBalance;
                $data['equities'][] = $item->lastEquity;
            }
            return ResponseService::apiResponse(200, '', $data);

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    /**
     * Get trading info
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function getAccountStatus(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'login'  => 'required|int|exists:accounts,login',
                'doc_id' => 'required|exists:account_certificates,doc_id'
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }


            $controller    = new \App\Http\Controllers\AccountController();
            $account       = Account::with('plan')->whereLogin($request->login)->first();
            $cardDetails   = json_decode($controller->endpoint($account->id)->content(), 1);
            $planRules     = $account->planRules();
            $topThreePairs = CertificateService::topThreePairs($account);
            $sumVolume     = $account->thisCycleTrades->sum('volume');

            $latestSubscription    = $account->latestSubscription;
            $request['start_date'] = $latestSubscription->created_at;
            $request['end_date']   = $latestSubscription->ending_at;
            $tradingInfo           = [];
            $certificateInfo       = CertificateService::getCertificateInfo($request);
            $certificateData       = CertificateService::dateWiseTradingInfo($request);
            foreach ($certificateData as $item) {
                $tradingInfo['dates'][]    = $item->metricDate;
                $tradingInfo['balances'][] = $item->lastBalance;
                $tradingInfo['equities'][] = $item->lastEquity;
            }
            $accountInfo = [
                'avgProfit'            => round($account->thisCycleTrades->where('profit', '>=', 0)->avg('profit'), 2),
                'avgLoss'              => round($account->thisCycleTrades->where('profit', '<', 0)->avg('profit'), 2),
                'avgLot'               => $sumVolume > 0 ? round($sumVolume / $sumVolume) / 100 : 0, // avg volume
                'topThreePairs'        => $topThreePairs,
                'maxDailyLoss'         => round($cardDetails['maxDailyLoss'], 2),
                'maxMonthlyLoss'       => round($cardDetails['maxMonthlyLoss'], 2),
                'tradingDay'           => $cardDetails['activeTradingDay'],
                'amount'               => $account->starting_balance,
                'DLL'                  => ($account->starting_balance * $planRules['DLL']['value']) / 100,
                'MLL'                  => ($account->starting_balance * $planRules['MLL']['value']) / 100,
                'PT'                   => $cardDetails['profitTarget'],
                'currentProfit'        => $account->blance - $account->starting_balance,
                'startDate'            => $latestSubscription->created_at,
                'endDate'              => $latestSubscription->ending_at,
                'modelName'            => $account->plan->type ?? "",
                'accountCertificateId' => $certificateInfo->id ?? "",
                'public_share_status'  => $certificateInfo->trading_public_share ?? 'no',
                'url'                  => $certificateInfo->url ?? null,
                'tradingInfo'          => $tradingInfo
            ];
            return ResponseService::apiResponse(200, '', $accountInfo);

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    public function generateCertificate($preview)
    {
        try {
            if (CertificateService::checkAlreadyExistCertificate($preview)) {
                return 1;
            }
            return CertificateService::generatePdfAndImage($preview);

        } catch (Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * Get trading info
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function getPublicAccountStatus(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'login'  => 'required|int|exists:accounts,login',
                'doc_id' => 'required'
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            $certificateInfo = CertificateService::getCertificateInfo($request);
            if (!$certificateInfo) {
                return ResponseService::apiResponse(422, 'Certificate info not found');
            }

            $controller    = new \App\Http\Controllers\AccountController();
            $account       = Account::whereLogin($request->login)->first();
            $cardDetails   = json_decode($controller->endpoint($account->id)->content(), 1);
            $planRules     = $account->planRules();
            $topThreePairs = CertificateService::topThreePairs($account);
            $sumVolume     = $account->thisCycleTrades->sum('volume');

            $tradingInfo        = [];
            $latestSubscription = $account->latestSubscription;
            if ($certificateInfo->trading_public_share == 'yes') { // if public shareable
                $request['start_date'] = $latestSubscription->created_at;
                $request['end_date']   = $latestSubscription->ending_at;
                $certificateData       = CertificateService::dateWiseTradingInfo($request);
                foreach ($certificateData as $item) {
                    $tradingInfo['dates'][]    = $item->metricDate;
                    $tradingInfo['balances'][] = $item->lastBalance;
                    $tradingInfo['equities'][] = $item->lastEquity;
                }
            }

            $accountInfo = [
                'avgProfit'      => round($account->thisCycleTrades->where('profit', '>=', 0)->avg('profit'), 2),
                'avgLoss'        => round($account->thisCycleTrades->where('profit', '<', 0)->avg('profit'), 2),
                'avgLot'         => $sumVolume > 0 ? round($sumVolume / $sumVolume) / 100 : 0, // avg volume
                'topThreePairs'  => $topThreePairs,
                'maxDailyLoss'   => round($cardDetails['maxDailyLoss'], 2),
                'maxMonthlyLoss' => round($cardDetails['maxMonthlyLoss'], 2),
                'tradingDay'     => $cardDetails['activeTradingDay'],
                'amount'         => $account->starting_balance,
                'DLL'            => ($account->starting_balance * $planRules['DLL']['value']) / 100,
                'MLL'            => ($account->starting_balance * $planRules['MLL']['value']) / 100,
                'PT'             => $cardDetails['profitTarget'],
                'currentProfit'  => $account->blance - $account->starting_balance,
                'startDate'      => $latestSubscription->created_at,
                'endDate'        => $latestSubscription->ending_at,
                'modelName'      => $account->plan->type ?? "",
                'url'            => $certificateInfo->url,
                'tradingInfo'    => $tradingInfo
            ];
            return ResponseService::apiResponse(200, '', $accountInfo);

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    /**
     * Get trading info
     * @return JsonResponse|\Illuminate\Http\Response
     */

    public function updateShare(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'share'                  => 'required|int',
                'account_certificate_id' => 'required|int',
                'account_id'             => 'required|int'
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            if (!CertificateService::updateShare($request)) {
                return ResponseService::apiResponse(422, 'login or certificate id did not match');
            }
            return ResponseService::apiResponse(200, 'Update successfully');

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }

    /**
     * update public shareable or not in trading data
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function updateToggleInfo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'toggle_status'          => 'required',
                'account_certificate_id' => 'required|int',
                'account_id'             => 'required|int'
            ]);
            if ($validator->fails()) {
                return ResponseService::apiResponse(422, 'Invalid input', $validator->errors());
            }
            if (!CertificateService::updateToggle($request)) {
                return ResponseService::apiResponse(422, 'account or certificate id did not match');
            }
            return ResponseService::apiResponse(200, 'Update successfully');

        } catch (Exception $exception) {
            Log::error($exception);
            return ResponseService::apiResponse(500, "Internal server error");
        }
    }


}
