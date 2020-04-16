<?php

return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'api/runtime',
            'docs/runtime',
            'docs/web/assets',
            'console/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'api/runtime',
            'docs/runtime',
            'docs/web/assets',
            'console/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
    ],
];
