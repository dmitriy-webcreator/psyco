<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 21.06.2017
 * Time: 23:06
 */

namespace app\models;

use yii\db\ActiveRecord;
use app\models\UsersConversation;
use yii\db\Query;

class Conversations extends ActiveRecord {


    public static function getConversationByUsersId($user_id_1 = '', $user_id_2 = '', $asArray = false){
        $sql = "SELECT `conversation_id` FROM `users_conversation` WHERE `user_id` = '$user_id_1' AND `conversation_id` IN(SELECT `conversation_id` FROM `users_conversation`WHERE `user_id` = '$user_id_2')";
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public static function getUserConversations($user_id = ''){
        // Достаём пользователей, а не ID бесед, в будущем если нужно будет
        // делать конференции и добавлять других учасников в беседу, нужно будет доставать
        // ID бесед и создать метод для получения юзеров и сообщений из этих бесед

        $sql = "SELECT uc.conversation_id, u.id AS user_id, u.username, u.full_name, u.account_type FROM `users_conversation` uc
                LEFT JOIN `users` u ON uc.`user_id` = u.`id`
                WHERE `conversation_id` IN(SELECT `conversation_id` FROM `users_conversation` WHERE `user_id` = '$user_id') AND `user_id` <> '$user_id'";
        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        foreach($result as $key=>$item) $result[$key] = (object) $item;
        return $result;
    }

    public function createConversation($user_id_1 = '', $user_id_2 = ''){
        // Создаем новую беседу
        $this->name = 'USER ID: '.$user_id_1.' AND USER ID: '.$user_id_2;
        $this->date = time();
        $result = $this->save();
        if(!$result) return false;

        return $result;
    }

}