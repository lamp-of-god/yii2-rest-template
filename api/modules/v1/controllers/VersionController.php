<?php

declare(strict_types=1);

namespace api\modules\v1\controllers;

use api\controllers\BaseController;


class VersionController extends BaseController
{
    /**
     * @SWG\Get(path="/version",
     *     tags={"Common"},
     *     summary="Returns actual API version",
     *     @SWG\Response(
     *         response = 200,
     *         description = "Actual API version",
     *     ),
     * )
     */
    public function actionIndex(): array
    {
        return $this->success('1.0.0');
    }
}
