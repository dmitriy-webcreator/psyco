<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 10.07.2017
 * Time: 2:06
 */


namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\thirdparty\MobizonApi;



class SMSProvider extends ActiveRecord{

    public static $apiKey = '832c08523744a434925be71a9f249f1794722991';
    public static $wait = 180; // интервал перед отправкой повторной СМС в секундах (180 = 3 минуты)

    public static function tableName()
    {
        return 'sms_service';
    }

    public static function sendConfirmation($phone = ''){

        if(!$row = self::findOne(['phone' => $phone])){
            // Если код подтверждения ещё не создан

            $code = rand(10000, 99999);
            $send_sms = self::sendSms($phone, 'Ваш код подтверждения: '.$code); // пытаемся отправить смс

            if($send_sms['result'] != 'error'){
                $sended = $send_sms['data'];
                $sms = new self();
                $sms->phone = $phone;
                $sms->session_id = $sended->messageId;
                $sms->code = $code;
                if($sms->save()) return ['result' => 'success', 'data' => ['session_id' => $sended->messageId] ];
            }else{
                return ['result' => 'error', 'data' => 'SMS CENTER: '.$send_sms['data']];
            }

        }
        return ['result' => 'error', 'data' => 'Code for this phone exist. Use method resend'];
    }

    public static function reSendConfirmation($phone = '', $session_id){

        if($row = self::findOne(['phone' => $phone, 'session_id' => $session_id])){
            // если код для этого телефона в базе присутствует, получаем статус СМС

            $updated = strtotime($row->date);

            $time_to_send_sms = time() - $updated; // сколько прошло времени от последней отправки СМС

            if( $time_to_send_sms < self::$wait){ // не отправляем SMS повторно, если не прошло время $wait
                return ['result' => 'error', 'data' => 'Слишком часто'];
            }

            $send_sms = self::sendSms($phone, 'Ваш код подтверждения: '.$row->code); // пытаемся отправить смс
            $send_sms['result'] = true;
            if($send_sms['result'] == true){
                $sended = $send_sms['data'];
                $row->session_id = $sended->messageId;
                if($row->update()) return ['result' => 'success', 'data' => ['session_id', $sended->messageId]];
            }else{
                return ['result' => 'error', 'data' => $send_sms['data']];
            }
        }

        return ['result' => 'error', 'data' => 'session_id or phone is incorrect'];
    }

    public static function checkConfirmation($phone = '', $session_id = '', $code = ''){
        if($row = self::findOne(['phone' => $phone, 'session_id' => $session_id])){
            if($row->code != $code){
                return ['result' => 'error', 'data' => 'invalid code'];
            }
            $row->delete();
            return ['result' => 'success', 'data' => 'confirmed'];
        }

        return ['result' => 'error', 'data' => 'invalid session_id'];
    }

    public static function sendSms($phone = '', $msg = ''){

        $phone = preg_replace("/[^0-9]/", "", $phone);
        if($phone == '' || $msg == '') return false;

        $api = new MobizonApi(self::$apiKey);

        $send = $api->call('message',
            'sendSMSMessage',
            array(
                'recipient' => $phone,
                'text'      => $msg
            )
        );

        if($send){
            return ['result' => 'success', 'data' => $api->getData()];
        }else{
            return ['result' => 'error', 'data' => $api->getMessage()];
        }

    }


}