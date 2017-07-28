<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 14.07.2017
 * Time: 19:20
 */

namespace app\modules\api\controllers;

use app\models\SMSProvider;
use app\models\Users;
use yii\rest\ActiveController;

class UsersController extends ActiveController {
    public $modelClass = 'app\models\Users';

    public function actionRegister(){

        $phone = \Yii::$app->request->post('phone');
        $phone = preg_replace("/[^0-9]/", "", $phone);

        $password = Users::encodePassword(Users::stringGenerator(5)); // Генерим случайный пароль

        $full_name  = \Yii::$app->request->post('full_name');
        $age        = \Yii::$app->request->post('age');
        $sex        = \Yii::$app->request->post('sex');
        $languages  = \Yii::$app->request->post('languages');

        if(!$phone || $phone == ''){
            return ['result'=>'error', 'data' => 'Phone is missed'];
        }

        // Добавляем пользвателя в базу
        $user = new Users();

        $user->username     = $phone;
        $user->password     = $password;
        $user->phone        = $phone;
        $user->full_name    = $full_name;
        $user->age          = $age;
        $user->sex          = Users::$users_labels['sex'][$sex];
        $user->languages    = Users::$users_labels['languages'][$languages];
        $user->account_type = Users::$users_labels['account_type']['client'];
        $user->save();

        // Отлавливаем ошибки базы
        if($user->hasErrors()){
            return ['result' => 'error', 'data' => $user->getErrors()];
        }

        // Отправляем код подтверждения
        $sms = SMSProvider::sendConfirmation($phone);

        if($sms['result'] != 'success'){
            return ['result' => 'error', 'data' => $sms['data']];
        }

        return ['result' => 'success', 'data' => ['phone' => $phone, 'session_id' => $sms['data']['session_id']]];
    }

    public function actionConfirmation(){

        $phone = \Yii::$app->request->post('phone');
        $phone = preg_replace("/[^0-9]/", "", $phone);

        $session_id = \Yii::$app->request->post('session_id');
        $code       = \Yii::$app->request->post('code');

        if(!$session_id && !$code){
            return ['result' => 'error', 'data' => 'session_id or code is missed'];
        }

        $confirmation = SMSProvider::checkConfirmation($phone, $session_id, $code);

        if($confirmation['result'] != 'success'){
            return $confirmation;
        }

        $password   = Users::stringGenerator(5);

        $user = Users::findOne(['phone' => $phone]);
        $user->confirmed    = true;
        $user->password     = Users::encodePassword($password);
        $user->update();

        if($user->hasErrors()){
            return ['result' => 'error', 'data' => $user->getErrors()];
        }

        // Отправляем сгенерированный пароль СМС кой
        SMSProvider::sendSms($user->phone, 'Ваш пароль: '.$password);

        return ['result' => 'success', 'data' => $user];

    }



}