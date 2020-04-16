<?php

namespace api\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class BaseController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    public function actionError(): array
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return ['error' => 'Resource not found'];
        }
        return ['error' => $exception->getMessage()];
    }
}
