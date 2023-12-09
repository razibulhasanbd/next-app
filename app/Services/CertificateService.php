<?php

namespace App\Services;

use App\Constants\CertificateConstants;
use App\Models\Account;
use App\Models\AccountCertificate;
use App\Models\AccountMetric;
use App\Models\Trade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use DB;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Mpdf\MpdfException;

class CertificateService
{

    /**
     * The function return all certificate info
     * @param int $loginId
     * @return object
     */
    public static function certificateInfo(int $loginId): object
    {
        return AccountCertificate::with(['certificate.type'])->whereHas('account', function ($q) use ($loginId) {
            $q->where('login', $loginId);
        })->get();
    }

    /**
     * Date range wise account metric return
     * @param $request
     * @return object
     */
    public static function dateWiseTradingInfo($request): object
    {
        return AccountMetric::whereHas('account', function ($q) use ($request) {
            $q->where('login', $request->login);
        })->whereBetween('metricDate', [$request->start_date, $request->end_date])
            ->get();
    }

    /**
     * return account certificate count
     * @param array $preview
     * @return int
     */
    public static function checkAlreadyExistCertificate(array $preview): int
    {
        return AccountCertificate::where('account_id', $preview['accountId'])
            ->where('certificate_id', $preview['certTypeId'])
            ->whereNotIn('certificate_id', [3, 4]) // 3,4 = Payout I , Payout II
            ->count();
    }

    /**
     * Generate pdf and convert to image
     * @param array $preview
     * @return string
     * @throws MpdfException
     * @throws \ImagickException
     * @throws \Exception
     */
    public static function generatePdfAndImage(array $preview): string
    {
        $docId        = self::generateUniqueId();
        $filePath     = self::makeDirectory();
        $filePathName = str_replace(' ', '-', $preview['fullname']) . '@aa@' . $docId;

        $defaultConfig             = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs                  = $defaultConfig['fontDir'];
        $defaultFontConfig         = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData                  = $defaultFontConfig['fontdata'];
        $mpdf                      = new \Mpdf\Mpdf(
            [
                'tempDir'       => storage_path('tempdir'),
                'fontDir'       => array_merge($fontDirs, [
                    public_path('fonts'), // to find like /public/fonts/OleoScript-Regular.ttf
                ]),
                'fontdata'      => $fontData + [
                        'oleo-script' => [
                            'R' => 'OleoScript-Regular.ttf'
                        ],

                        'oswald-regular' => [
                            'R' => 'Oswald-Regular.ttf'
                        ],
                    ],
                'margin_left'   => 10,
                'margin_right'  => 10,
                'margin_top'    => 0,
                'margin_bottom' => 0,
                'margin_header' => 0,
                'margin_footer' => 0,
                'format'        => [230, 160]
            ]
        );
        $mpdf->setAutoBottomMargin = 'stretch';
        $html                      = self::certificateTemplate($preview, $docId);
        $mpdf->WriteHTML($html);
        $pdfFilePath = $filePath . $filePathName . '.pdf';
        $file = $mpdf->Output($pdfFilePath, 'S');

        Storage::disk('utility-files')->put($pdfFilePath, $file, 'public');
        $pdfFullUrl = env('DO_URL').$pdfFilePath;

        $imageFilePath = $filePath . $filePathName  . '.jpg';
        $im            = new Imagick();
        $im->setResolution(300, 300);
        $im->readImage($pdfFullUrl);
        $im->setImageFormat('jpg');
        $imageFIle = $im->getImageBlob();
        $im->clear();
        $im->destroy();
        Storage::disk('utility-files')->put($imageFilePath, $imageFIle, 'public');
        return env('DO_URL').$imageFilePath;
    }

    /**
     * return render template
     * @param array $preview
     * @param string $docId
     * @return string
     */
    private static function certificateTemplate(array $preview, string $docId): string
    {
        $templateId = $preview['certificate_data']['variant'] ?? "";
        return view("admin.accountCertificates.template.$templateId", compact('preview', 'docId'))->render();
    }

    /**
     * return unique id
     * @return string
     * @throws \Exception
     */
    public static function generateUniqueId(): ?string
    {
        $last6DigitUnique = substr(uniqid(), -5);
        $bytes            = random_bytes(4);
        return bin2hex($bytes) . $last6DigitUnique;
    }

    /**
     * Return single certificate url
     * @param $request
     * @return string
     */
    public static function singleCertificateInfo($request): ?string
    {
        return AccountCertificate::with(['certificate.type'])->whereHas('account', function ($q) use ($request) {
            $q->where('login', $request->login);
        })->where('doc_id', $request->doc_id)->value('url');
    }


    /**
     * top three symbol return
     * @param Account $account
     * @return array
     */
    public static function topThreePairs(Account $account): array
    {
        return Trade::where('account_id', $account->id)
            ->where('created_at', '>=', $account->currentSubscription->created_at)
            ->select(['symbol', DB::raw('COUNT(symbol) AS symbolCount')])
            ->groupBy('symbol')
            ->orderBy("symbolCount", "DESC")
            ->take(3)
            ->get()
            ->toArray();
    }

    /**
     * month wise directory create for pdf and image
     * @return string
     */
    public static function makeDirectory(): string
    {
        $filePath = 'Certificates/' . date("Y") . "/" . date("m") . "/";
        return $filePath;
//        dd(in_array($filePath, Storage::disk('utility-files')->directories($filePath)));
//        if(!in_array($filePath, Storage::disk('utility-files')->directories($filePath))){
//            Storage::disk('utility-files')->makeDirectory($filePath);
//        }
    }

    /**
     * Update account certificate when share certificate a user
     * @param $request
     * @return void
     */
    public static function updateShare($request): bool
    {
        $accountCertificate = AccountCertificate::where('id', $request->account_certificate_id)
            ->where('account_id', $request->account_id)
            ->first();
        if (!$accountCertificate) {
            return false;
        }
        $accountCertificate->share = $request->share == 1 ? $request->share: 0;
        $accountCertificate->save();
        return true;
    }

    /** return certificate url
     * @param $request
     * @return string|null
     */
    public static function getCertificateInfo($request): ?object
    {
        $loginId = $request->login;
        return AccountCertificate::whereHas('account', function ($q) use ($loginId) {
            $q->where('login', $loginId);
        })->where('doc_id', $request->doc_id)->first();
    }

    /**
     * update public shareable or not in trading data
     * @param $request
     * @return void
     */
    public static function updateToggle($request): bool
    {
        $accountCertificate = AccountCertificate::where('id', $request->account_certificate_id)
            ->where('account_id', $request->account_id)
            ->first();
        if (!$accountCertificate) {
            return false;
        }
        $accountCertificate->trading_public_share = $request->toggle_status;
        $accountCertificate->save();
        return true;
    }

}

