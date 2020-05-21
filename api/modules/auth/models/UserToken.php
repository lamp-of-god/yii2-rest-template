<?php

declare(strict_types=1);


namespace api\modules\auth\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * User token model.
 *
 * @property integer $user_id
 * @property string $token
 * @property integer $expired_at
 *
 * @property User $user
 */
class UserToken extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['user_id', 'integer'],
            ['user_id', 'integer'],
            ['user_id', 'exist', 'targetRelation' => 'user'],

            ['token', 'required'],
            ['token', 'string'],
            ['token', 'unique'],

            ['expired_at', 'integer'],
            ['expired_at', 'default', 'value' => 0],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
