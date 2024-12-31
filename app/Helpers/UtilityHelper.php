<?php

namespace App\Helpers;

class UtilityHelper {
    public static function ping($instance = null) {
        $response = [
            'status_code' => 200,
            'status' => 'success',
            'details' => 'Connection stabilized successfully!',
            'server' => 'Timetable API Rest',
            'version' => 'v1 - 1.0',
        ];

        if ($instance != null) {
            $response['instance'] = $instance;
        }

        return response()->json($response, 200);
    }
}
