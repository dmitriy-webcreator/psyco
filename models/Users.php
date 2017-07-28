<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

class Users extends ActiveRecord implements \yii\web\IdentityInterface
{
//    public $id;
//    public $username;
//    public $password;
    public $authKey;
    public $accessToken;
    public static $user_hash;
    private static $salt = 'yI8&4*0){oA,|Ò¥';

    public static $users_labels = [
        'account_type'  => [
            'client' => 1,
            'consultant' => 2,
            'administrator' => 3
        ],

        'status'        => [
            'online' => 1,
            'away' => 2,
            'busy' => 3
        ],

        'sex'           => [
            'male' => 1,
            'female' => 2
        ],

        'languages'     => [
            'ru' => 1,
            'kz' => 2
        ],
    ];

    public function rules()
    {
        return [
            [['phone'], 'unique'],
            [['phone', 'password'], 'required'],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['password'], $fields['hash']);

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = self::findOne(['username' => $username]);
        new static($user);
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === self::encodePassword($password);
    }

    public static function getCurrentUser(){
        return \Yii::$app->user->identity;
    }

    public static function getUserByHash($hash = ''){
        return self::findOne(['hash' => $hash]);
    }

    public static function getUser($id = '', $account_type = ""){
        return self::findOne(['id' => $id, 'account_type' => $account_type]);
    }

    public static function updateLastActivity($id = ''){

        $hash = self::generateHash();

        if(!empty($id)){

        }else{
            // текущий пользователь
            $id = \Yii::$app->user->identity->id;
            self::$user_hash = $hash;
        }

        $user = self::findOne(['id' => $id]);
        $user->last_activity = time();
        $user->hash = $hash;
        $user->update();
    }

    public static function getOnlineUsers(){
        // last_activity убрать из базы если не используется
        return self::find()
            ->select(['account_type'])
            ->where(['status'=>self::$users_labels['status']['online']])
            ->all();
    }

    public static function getTopUsers($account_type = '', $limit = 5){
        return self::find()
            ->select(['full_name', 'account_type', 'consultation_time', 'id'])
            ->orderBy('consultation_time DESC')
            ->limit($limit)
            ->where( ['account_type' => $account_type] )
            ->all();
    }

    public static function ConsultationTimeFormat($time = ''){
        return ceil($time/60) . ' мин.';
    }

    public static function getLabels(){
        return self::$users_labels;
    }

    public static function getUserAvatarUrl($user_id = ''){

        if($user_id == ''){
            $current_user = Users::getCurrentUser();
            $user_id = $current_user->id;
        }
        $avatar_url = '/uploads/avatars/'.$user_id.'/avatar.jpg';

        if(file_exists(Url::to('@webroot'.$avatar_url))){
            return Url::to('@web'.$avatar_url);
        }else{
            return Url::to('@web/assets/svg/admin_icon.svg');
        }
    }

    public static function theAvatar($user_id = ''){
        return Html::img(self::getUserAvatarUrl($user_id));
    }

    public static function generateHash(){
        return sha1(md5(time() . '$8f#9^%8Hf').time());
    }

    public static function encodePassword($password = ''){
        return sha1(sha1(self::$salt).sha1($password));
    }

    public static function stringGenerator($length = '5'){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


}
