<?php

namespace api\controllers;

use Yii;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;


class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];

        // Disable rate limiter because otherwise we need to implement User entity which is not common case.
        unset($behaviors['rateLimiter']);

        // Enable CORS to enable Swagger docs to exec test queries.
        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Allow-Headers' => ['content-type'],
            ],
        ];

        return $behaviors;
    }

    public function actionError(): array
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return ['error' => 'Resource not found'];
        }
        return ['error' => $exception->getMessage()];
    }

    protected function success(?array $result = null): array
    {
        return [
            'error'  => null,
            'result' => $result,
        ];
    }
}
