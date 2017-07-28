<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 18.06.2017
 * Time: 20:20
 */

namespace app\controllers;

use app\models\RegisterForm;
use app\models\Users;
use Yii;
use yii\web\Controller;
use app\models\LoginForm;

class AccountController extends Controller
{
    public function actionIndex(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['panel/']);
        }else{
            return $this->redirect(['account/login']);
        }
    }

    public function actionLogin(){
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['panel/']);
        }

        $model = new LoginForm();
        $users = new Users;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $users->updateLastActivity();
            return $this->redirect(['panel/']);
        }
        return $this->render('login', [
            'model' => $model
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout(){
        Yii::$app->user->logout();
        return $this->redirect(['account/login']);
    }

    public function actionRegister(){

        $model = new RegisterForm();

        if(isset($_POST['RegisterForm'])){
            $model->attributes = \Yii::$app->request->post('RegisterForm');

            if($model->validate() && $model->saveUser()){
                echo 'Вы зарегестрированы!';

            }
        }



        return $this->render('register', ['model' => $model]);
    }

}