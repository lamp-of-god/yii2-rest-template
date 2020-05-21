<?php

use \yii\console\controllers\MigrateController;


$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);


return array_merge_recursive([
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => ['class' => 'api\modules\v1\Module'],
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'base/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET v1/version' => 'v1/version/index',
            ],
        ]
    ],
    'params' => $params,
], [  // Delete/comment if app does not require authentication
    'controllerMap' => [
        'migrate-auth' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => ['api\modules\auth\migrations'],
            'migrationPath' => null,
        ],
    ],
    'modules' => [
        'auth' => ['class' => 'api\modules\auth\Module'],
    ],
    'components' => [
        'urlManager' => [
            'rules' => [
                'POST v1/auth/sign-in' => 'auth/auth/sign-in',
                'POST v1/auth/sign-up' => 'auth/auth/sign-up',
                'POST v1/auth/sign-out' => 'auth/auth/sign-out',
            ],
        ]
    ],
]);
