<?php

declare(strict_types=1);


namespace api\modules\auth\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;


/**
 * User model.
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property integer $level
 */
class User extends ActiveRecord
{
    const LEVEL_ADMIN = 0;
    const LEVEL_MANAGER = 1;
    const LEVEL_USER = 2;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
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
            ['id', 'integer'],

            ['login', 'required'],
            ['login', 'string'],
            ['login', 'unique'],

            ['password', 'required'],
            ['password', 'string'],

            ['level', 'integer'],
            ['level', 'in', 'range' => [
                User::LEVEL_ADMIN,
                User::LEVEL_MANAGER,
                User::LEVEL_USER,
            ]],
            ['level', 'default', 'value' => User::LEVEL_ADMIN],
        ];
    }
}
