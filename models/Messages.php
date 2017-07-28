<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 21.06.2017
 * Time: 16:01
 */

namespace app\models;

use Faker\Provider\DateTime;
use yii\db\ActiveRecord;
use yii\db\Query;
use app\models\Conversations;

class Messages extends ActiveRecord {

    public static function getUserMessages($user_id = ''){
//        $query = new Query();
//        $query->select('u.full_name, u.account_type, m.date, m.message, m.from_user_id, m.status')
//            ->from('messages m')
//            ->leftJoin('users u', 'u.id=m.from_user_id')
//            ->where('m.to_id = '.$user_id);
//        $command = $query->createCommand();
//        return $command->queryAll();
    }

    public static function getMessagesFromConversation($conversation_id = ''){
        return self::find()->where(['conversation_id' => $conversation_id])->orderBy('date')->all();
    }

    public static function formatDateMessage($time = ''){

            $cur_time = time();
            $time = (strtotime($time) ? strtotime($time) : $time);
            $yesterday = ($cur_time - 86400);

            if(date('d', $time) == date('d', $cur_time)){
                // Today
                return date('H:i', $time);
            }elseif(date('d',$yesterday) == date('d', $time) ){
                return 'Вчера, '.date('H:i', $time);
            }

        return date('d/m', $time).', '.date('H:i', $time);

    }

    public static function getUserUnreadMessages($user_id = ''){
        $sql = "SELECT * FROM `messages` m WHERE m.`conversation_id` IN(SELECT uc.conversation_id FROM `users_conversation` uc
                LEFT JOIN `users` u ON uc.`user_id` = u.`id`
                WHERE `conversation_id` IN(SELECT `conversation_id` FROM `users_conversation` WHERE `user_id` = '$user_id') AND `user_id` <> '$user_id') AND m.`status` = 0 AND m.`user_id` <> '$user_id'";

        $connection = \Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        foreach($result as $key=>$item) $result[$key] = (object) $item;
        return $result;
    }

    public static function setMessagesToRead($conversation_id = '', $user_id = ''){
        return self::updateAll(['status' => '1'], ['AND', 'conversation_id='.$conversation_id,'user_id<>'.$user_id]);
    }

    public function saveMessage($user_id = '', $conversation_id = '', $message = ''){
        $this->user_id = $user_id;
        $this->conversation_id = $conversation_id;
        $this->status = false;
        $this->message = $message;
        $result = $this->save();
        return $result;
    }

    public static function createHtml($data = array()){
        $html = '';

        if($data['block'] == 'message'){
            $message = $data['data'];

            $html .= '<div class="message '.($message->status ? '' : 'unread').'" data-msg_id="'.$message->id.'">';
                $html .= '<div class="'.$data['direction_class'].'">'.$message->message.'<div class="time">'.self::formatDateMessage($message->date).'</div></div>';
            $html .= '</div>';
        }

        return $html;
    }

}