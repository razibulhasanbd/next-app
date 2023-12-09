<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class CertificateApiCallException extends BaseException {
    public function __construct($message = "", $details = "", $code = 0, Throwable $previous = null)
    {
        $this->message = 'Customer Api call';

        if (!blank($message)) {
            $this->message = $message;
        }

        parent::__construct($details, $code, $previous);
    }
}
