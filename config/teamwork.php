<?php

return [
    'base_url' => env('TEAMWORK_BASE_URL', 'https://yourcompany.teamwork.com'),
    'webhook_url' => 'teamwork/webhook',
    'desk' => [
        'token' => env('TEAMWORK_DESK_API_TOKEN'),
        'webhook_secret' => env('TEAMWORK_DESK_WEBHOOK_SECRET'),
        'webhook_handlers' => [
            // 'event_type' => handler class
        ]
    ],
    'projects' => [
        'token' => env('TEAMWORK_PROJECTS_API_TOKEN'),
    ]
];
