<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\User;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $age;
    public $phone;
    public $education;
    public $experience;
    public $sex;
    public $languages;
    public $tin;
    public $status;
    public $rating;
    public $consultation_time;
    public $conversation_theme;
    public $account_type;
    public $balance;
    public $balance_spent;
    public $avatar_url;
    public $remember_token;
    public $last_activity;
    public $hash;

    public function rules(){
       return [
           [['username', 'password'], 'required'],
           ['username', 'unique', 'targetClass' => 'app\models\Users']
       ];
    }

    public function saveUser(){
        $users = new Users();
        $users->username = $this->username;
        $users->password = Users::generatePassword($this->password);
        return $users->save();
    }


}

