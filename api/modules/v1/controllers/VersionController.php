<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;


class VersionController extends BaseController
{
    public function actionIndex(): array
    {
        return $this->success('1.0.0');
    }
}
