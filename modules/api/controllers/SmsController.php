<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 13.07.2017
 * Time: 0:18
 */

namespace app\modules\api\controllers;

use yii\web\Response;
use yii\web\Controller;
use app\models\SMSProvider;
use app\models\Users;
use yii\data\ActiveDataProvider;


class SmsController extends Controller {

    public function actionIndex(){
        return Users::getCurrentUser();
    }

    public function actionTest(){
        return '321';
    }

    public function actionCreate(){
        echo '321';
    }

}