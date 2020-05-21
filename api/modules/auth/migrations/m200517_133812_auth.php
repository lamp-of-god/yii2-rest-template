<?php

namespace api\modules\auth\migrations;

use api\modules\auth\models\User;
use yii\db\Migration;


/**
 * Class m200517_133812_auth
 */
class m200517_133812_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned(),
            'login' => $this->string(),
            'password' => $this->string(),
            'level' => $this->tinyInteger(),
            'created_at' => $this->integer()->unsigned(),
            'updated_at' => $this->integer()->unsigned(),
        ]);

        $this->createIndex('login', 'users', 'login', true);

        $this->createTable('users_tokens', [
           'user_id' => $this->primaryKey()->unsigned(),
           'token' => $this->string(),
           'expired_at' => $this->integer()->unsigned(),
           'created_at' => $this->integer()->unsigned(),
           'updated_at' => $this->integer()->unsigned(),
        ]);

        $this->addForeignKey(
            'users_tokens-user_id-users-id',
            'users_tokens', 'user_id',
            'users', 'id',
            'CASCADE', 'CASCADE',
        );

        $this->createIndex('token', 'users_tokens', 'token');
        $this->createIndex('expired_at', 'users_tokens', 'expired_at');

        $this->insert('users', [
            'id' => 1,
            'login' => 'admin',
            'password' => '$2y$10$JvyZRhdAylqt4LvhQr32/um.vDh.fIHvsJnPFKwFiM/W87NdJ4XCy',
            'level' => User::LEVEL_ADMIN,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users_tokens');
        $this->dropTable('users');
    }
}
