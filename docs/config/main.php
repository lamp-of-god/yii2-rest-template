<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php',
);

return [
    'id' => 'docs',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'docs\controllers',
    'modules' => [
        'v1' => ['class' => 'docs\modules\v1\Module'],
    ],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ]
    ],
    'params' => $params,
];
