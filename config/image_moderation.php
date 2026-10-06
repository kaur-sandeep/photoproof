<?php

return [
    'enabled' => env('IMAGE_MODERATION_ENABLED', true),
    'url' => env('IMAGE_MODERATION_URL', 'http://127.0.0.1:8001'),
    'timeout' => env('IMAGE_MODERATION_TIMEOUT', 30),
    'connect_timeout' => env('IMAGE_MODERATION_CONNECT_TIMEOUT', 5),
    'fail_closed' => env('IMAGE_MODERATION_FAIL_CLOSED', true),
];
