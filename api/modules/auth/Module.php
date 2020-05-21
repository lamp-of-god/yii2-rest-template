<?php

namespace api\modules\auth;

use api\modules\auth\models\UserToken;


class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\auth\controllers';


    /**
     * Checks whether given token belongs to with at lease "level" access.
     *
     * @param string $token     Token to check.
     * @param null|int $level   Optional level to check auth for.
     *
     * @return bool    Yes or no.
     */
    public static function checkAuth(string $token, ?int $level = null): bool
    {
        $token = UserToken::findOne(['token' => $token]);
        if (empty($token)) {
            return false;
        }
        if ($token->expired_at < time()) {
            return false;
        }
        if (($level !== null) && $token->user->level > $level) {
            return false;
        }
        return true;
    }
}
