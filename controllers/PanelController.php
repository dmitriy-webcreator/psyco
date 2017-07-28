<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 02.05.2017
 * Time: 1:27
 */

namespace app\controllers;

use app\models\Analytics;
use app\models\SMSProvider;
use app\models\UploadForm;
use app\models\Users;
use app\models\Conversations;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\helpers\Url;


class PanelController extends Controller
{

    public $ajaxUrl;

    public function beforeAction($action)
    {

        $this->layout = 'panel';
        $this->ajaxUrl = Url::toRoute(['ajax/'], true);

        Users::updateLastActivity();
        return $action;
    }

    public function actionIndex(){

        $users = Users::getOnlineUsers();

        $topUsers['clients'] = Users::getTopUsers(Users::$users_labels['account_type']['client']);
        $topUsers['consultants'] = Users::getTopUsers(Users::$users_labels['account_type']['consultant']);


        $online[Users::$users_labels['account_type']['client']] = 0;
        $online[Users::$users_labels['account_type']['consultant']] = 0;
        $online[Users::$users_labels['account_type']['administrator']] = 0;

        foreach ($users as $obj){
            $online[$obj->account_type]++;
        }
        return $this->render('control_panel', ['online' => $online, 'users_labels' => Users::$users_labels, 'topUsers' => $topUsers]);
    }

    public function actionClients(){
        $subaction = \Yii::$app->request->get('subaction'); // site.com/clients/{subaction}/

        switch ($subaction){
            case 'online':
                $clients = Users::find()
                    ->where(['account_type' => Users::$users_labels['account_type']['client']])
                    ->orderBy([
                        'status' => Users::$users_labels['status']['online'],
                        'consultation_time' => SORT_DESC
                    ])
                    ->all();
                break;

            case 'rating':
                $clients = Users::find()
                    ->where(['account_type' => Users::$users_labels['account_type']['client']])
                    ->orderBy('consultation_time DESC')
                    ->all();
                break;

            default:
                $clients = Users::findAll(['account_type' => Users::$users_labels['account_type']['client']]);

        }

        return $this->render('clients', ['clients' => $clients]);
    }

    public function actionConsultants(){
        $subaction = \Yii::$app->request->get('subaction'); // site.com/consultants/{subaction}/

        switch ($subaction){
            case 'add':
                $uploadModel = new UploadForm();

                return $this->render('add_consultant', ['model' => $uploadModel]);
            break;

            default:
                $consultants = Users::findAll(['account_type' => Users::$users_labels['account_type']['consultant']]);
                return $this->render('consultants', ['consultants' => $consultants]);
        }

    }

    public function actionMessages(){
        $current_user = Users::getCurrentUser();
        $conversations = Conversations::getUserConversations($current_user->id);
        return $this->render('messages', ['conversations' => $conversations]);
    }

    public function getUserAvatarUrl($user_id = ''){
        return Users::getUserAvatarUrl($user_id);
    }

    public function  theAvatar($user_id = ''){
        return Users::theAvatar($user_id);
    }

    public function getAccountType($account_type_id = ''){
        if($account_type_id == null) return false;
        $account_types = Users::$users_labels['account_type'];
        $account_types = array_flip($account_types);
        switch ($account_types[$account_type_id]){
            case 'client': return 'Клиент';
                break;

            case 'consultant': return 'Консультант';
                break;

            case 'administrator': return 'Администратор';
                break;

            default: return 'Пользователь';
        };
    }

    public function getStatus($status = ''){
        if($status == null) return false;
        $statuses = array_flip(Users::$users_labels['status']);
        return $statuses[$status];
    }

    public function user_hash($user_id = ''){
        // для идентификации пользователя в  Ajax запросах
        if(empty($user_id)){
            return Users::$user_hash;
        }else{
            $user = Users::getUser($user_id);
            return $user->hash;
        }
        return false;
    }

    public function debug($data = array(), $exit = false){
        echo '<pre>';
            print_r($data);
        echo '</pre>';
        ($exit ? exit : '');
    }

    public function actionAnalytics(){
        $model = new Analytics();
        $connection = new Query();

//        $this->debug(date('Y-m-d',), true);
        for($i=0; $i < 100; $i++){
            $rows[] = [date('Y-m-d',strtotime('now-'.$i.'day')), rand(15, 200)];
        }

//        $this->debug($rows, true);
        $connection->createCommand()->batchInsert(Analytics::tableName(), ['date', 'consultation_time'], $rows)->execute();
    }

    public function actionSms(){
        $phone = Yii::$app->request->post('p');
        $code = Yii::$app->request->post('c');
        $resend = Yii::$app->request->get('resend'); // session_id

        if($phone && $code){
            $this->debug(SMSProvider::checkConfirmation($phone, $code));
        }elseif(isset($resend)){
            $this->debug(SMSProvider::reSendConfirmation('380993085537', $resend));
        }else{
            $this->debug(SMSProvider::sendConfirmation('380993085537'));
        }

    }

    public function actionGetanalytics(){
        $query = new Query();

        $analytics = $query->select(['date', 'consultation_time'])
            ->from(Analytics::tableName())
            ->all();


        foreach ($analytics as $item){
            $arr['По дням'][] = $item['date'];
        }
        return json_encode($arr);
        $this->debug($arr);
    }



}