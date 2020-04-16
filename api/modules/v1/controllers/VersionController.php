<?php

namespace api\modules\v1\controllers;

use api\controllers\DocsController;


class VersionController extends DocsController
{
    public function actionIndex(): array
    {
        return $this->success('1.0.0');
    }
}
