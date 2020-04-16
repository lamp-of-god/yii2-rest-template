<?php

namespace api\controllers;

use yii\web\Response;


class VersionController extends BaseController
{
    public function actionIndex(): array
    {
        return [
            'error'  => null,
            'result' => '1.0.0',
        ];
    }
}
