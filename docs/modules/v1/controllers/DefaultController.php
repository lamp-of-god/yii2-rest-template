<?php

namespace docs\modules\v1\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;


/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="api.test",
 *     basePath="/v1",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="API",
 *         @SWG\Contact(
 *             email="sergey_rus@bk.ru"
 *         ),
 *     ),
 * )
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => 'yii2mod\swagger\SwaggerUIRenderer',
                'restUrl' => Url::to(['default/json-schema']),
            ],
            'json-schema' => [
                'class' => 'yii2mod\swagger\OpenAPIRenderer',
                'cacheDuration' => 1,
                'cacheKey' => 'swagger',
                'scanDir' => [
                    Yii::getAlias('@api/modules/v1'),
                    Yii::getAlias('@api/modules/auth'),
                    Yii::getAlias('@docs/modules/v1/controllers'),
                ],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
