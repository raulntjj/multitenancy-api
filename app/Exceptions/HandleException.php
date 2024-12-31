<?php

namespace App\Exceptions;

use Exception;

class HandleException extends Exception {
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request) {
        return response()->json([
            'status_code' => $this->getCode() ?: 400,
            'status' => 'failed',
            'details' => $this->getMessage(),
        ], $this->getCode() ?: 400);
    }
}