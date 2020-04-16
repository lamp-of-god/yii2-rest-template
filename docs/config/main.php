<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php',
);

return [
    'id' => 'docs',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'docs\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => ['class' => 'docs\modules\v1\Module'],
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
            'errorAction' => \yii\web\ErrorAction::class,
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
];
