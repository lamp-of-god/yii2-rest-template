<?php

declare(strict_types=1);

namespace api\modules\auth\controllers;

use api\controllers\BaseController;
use api\modules\auth\models\User;
use api\modules\auth\models\UserToken;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;


/**
 * @SWG\SecurityScheme(
 *   securityDefinition="AuthToken",
 *   type="apiKey",
 *   name="token",
 *   in="query",
 * )
 */
class AuthController extends BaseController
{
    public function behaviors(): array
    {
        return parent::behaviors() + [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'sign-in'  => ['POST'],
                    'sign-up'  => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @SWG\Post(path="/auth/sign-in",
     *     tags={"Authorization"},
     *     summary="Performs authentication and returns access token if it was successful",
     *
     *     @SWG\Parameter(
     *       name="Login data",
     *       in="body",
     *       required=true,
     *       description="User login data",
     *       @SWG\Schema(
     *         required={"login", "password"},
     *         @SWG\Property(property="login", type="string", description="User login", example="admin"),
     *         @SWG\Property(property="password", type="string", description="User password", example="admin"),
     *         @SWG\Property(property="remember", type="integer", description="If set, token will be valid for given amount of seconds. If not, token will never expire until next call of auth method.", example="3600"),
     *       ),
     *     ),
     *
     *     @SWG\Response(
     *       response = 200,
     *       description = "Auth token",
     *       @SWG\Schema(
     *            type="object",
     *            required={"token"},
     *            @SWG\Property(property="token", type="string", description="Auth token"),
     *       )
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Empty login or password",
     *     ),
     *     @SWG\Response(
     *         response = 403,
     *         description = "User or password is invalid",
     *     ),
     * )
     */
    public function actionSignIn(): array
    {
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        if (empty($login)) {
            throw new BadRequestHttpException('Empty login');
        }
        if (empty($password)) {
            throw new BadRequestHttpException('Empty password');
        }

        $user = User::findOne(['login' => $login]);
        if (empty($user) || !password_verify($password, $user->password)) {
            throw new ForbiddenHttpException('Invalid login or password');
        }
        UserToken::deleteAll(['user_id' => $user->id]);

        $accessToken = new UserToken();
        $accessToken->user_id = $user->id;
        $accessToken->token = bin2hex(random_bytes(32));
        $remember = (int)\Yii::$app->request->post('remember');
        $accessToken->expired_at = time() + ($remember ? $remember : 86400 * 365);
        if (!$accessToken->save()) {
            throw new ServerErrorHttpException('Error on token saving');
        }

        return $this->success(['token' => $accessToken->token]);
    }

    /**
     * @SWG\Post(path="/auth/sign-up",
     *     tags={"Authorization"},
     *     summary="Registers new user",
     *     security={{"AuthToken":{}}},
     *
     *     @SWG\Parameter(
     *       name="Login data",
     *       in="body",
     *       required=true,
     *       description="User data",
     *       @SWG\Schema(
     *         required={"login", "password", "level"},
     *         @SWG\Property(property="login", type="string", description="User login", example="admin"),
     *         @SWG\Property(property="password", type="string", description="User password", example="admin"),
     *         @SWG\Property(property="level", type="integer", description="User level", example="0"),
     *       ),
     *     ),
     *
     *     @SWG\Response(
     *       response = 200,
     *       description = "Registration is successful",
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Empty login or password or invalid level",
     *     ),
     *     @SWG\Response(
     *         response = 403,
     *         description = "Invalid authorization token or insufficient rights",
     *     ),
     *     @SWG\Response(
     *         response = 409,
     *         description = "User with the same login already exists",
     *     ),
     * )
     */
    public function actionSignUp(): array
    {
        $token = (string)\Yii::$app->request->get('token');
        if (empty($token)) {
            throw new ForbiddenHttpException('Empty auth token');
        }
        if (!$this->module->checkAuth($token, User::LEVEL_ADMIN)) {
            throw new ForbiddenHttpException('Invalid auth token or insufficient rights');
        }

        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');
        $level = (int)\Yii::$app->request->post('level');
        if (empty($login)) {
            throw new BadRequestHttpException('Empty login');
        }
        if (empty($password)) {
            throw new BadRequestHttpException('Empty password');
        }
        if (!in_array($level, [
            User::LEVEL_ADMIN,
            User::LEVEL_MANAGER,
            User::LEVEL_USER,
        ])) {
            throw new BadRequestHttpException('Invalid level');
        }

        $user = new User();
        $user->login = $login;
        $user->password = password_hash($password, CRYPT_SHA512);
        $user->level = $level;
        if (!$user->save()) {
            throw new ConflictHttpException('User already exists');
        }

        return $this->success();
    }

    /**
     * @SWG\Post(path="/auth/sign-out",
     *     tags={"Authorization"},
     *     summary="Signs out authorized user",
     *     security={{"AuthToken":{}}},
     *
     *     @SWG\Response(
     *       response = 200,
     *       description = "Signing out is successful",
     *     ),
     *     @SWG\Response(
     *         response = 403,
     *         description = "Invalid authorization token",
     *     ),
     * )
     */
    public function actionSignOut(): array
    {
        $token = (string)\Yii::$app->request->get('token');
        if (empty($token)) {
            throw new ForbiddenHttpException('Empty auth token');
        }
        if (!$this->module->checkAuth($token)) {
            throw new ForbiddenHttpException('Token not found');
        }
        UserToken::deleteAll(['token' => $token]);
        return $this->success();
    }
}
