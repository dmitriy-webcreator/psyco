<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 15.07.2017
 * Time: 19:10
 */

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use yii\web\Controller;

class AccountController extends ActiveController {
    public $modelClass = 'app\models\Users';

    public function actionGettoken(){
        return '123';
    }

}