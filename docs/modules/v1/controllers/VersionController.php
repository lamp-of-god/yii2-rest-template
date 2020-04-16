<?php

namespace docs\modules\v1\controllers;

use docs\controllers\DocsController;


class VersionController extends DocsController
{
    public function actionIndex(): array
    {
        return $this->success('1.0.0');
    }
}
