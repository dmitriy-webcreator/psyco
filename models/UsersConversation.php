<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 21.06.2017
 * Time: 23:06
 */

namespace app\models;

use yii\db\ActiveRecord;

class UsersConversation extends ActiveRecord {

    public static function tableName()
    {
        return 'users_conversation';
    }

    public function addUserToConversation($conversation_id = '', $user_id = ''){
        $this->conversation_id = $conversation_id;
        $this->user_id = $user_id;
        return $this->save();
    }

    public function getUserConversations(){

    }

}