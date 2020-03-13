<?php

return [
    'projects' => [
        'base_url' => env('TEAMWORK_PROJECTS_BASE_URL'),
        'token' => env('TEAMWORK_PROJECTS_TOKEN'),
        'delay' => env('TEAMWORK_PROJECTS_DELAY', 65),
    ]
];