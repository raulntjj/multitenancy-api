<?php

namespace App\Exceptions;

use Exception;

class HandleException extends Exception {
    public function __construct($message, $code = 500) {
        parent::__construct($message, $code);
    }
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request) {
        return response()->json([
            'status_code' => $this->getCode(),
            'status' => 'error',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}