<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 20.06.2017
 * Time: 15:28
 */


namespace app\controllers;

use app\models\Analytics;
use app\models\Messages;
use app\models\UploadForm;
use yii\web\UploadedFile;
use Yii;
use yii\web\Controller;
use app\models\Users;
use app\models\Conversations;
use app\models\UsersConversation;
use yii\web\HttpException;
use yii\web\User;

class AjaxController extends Controller {

    function beforeAction($action){

        //sleep('1.5');
       if(\Yii::$app->request->isAjax){
           return $action;
       }else{
           throw new HttpException(404 ,'Page not found');
       }

       return false;

    }

    function actionIndex(){

//        $this->response('error', '', \Yii::$app->request->post());
        switch (\Yii::$app->request->post('action')){

            case 'getClient': return $this->getClient();
                break;

            case 'getConsultant': return $this->getConsultant();
                break;

            case 'sendUserMessage': return $this->sendMessage('user');
                break;

            case 'sendConversationMessage': return $this->sendMessage('conversation');
                break;

            case 'getMessagesFromConversation': return $this->getMessagesFromConversation();
                break;

            case 'getUnreadMessages': return $this->getUnreadMessages();
                break;

            case 'setMessagesToRead': return $this->setMessagesToRead();
                break;

            case 'addConsultant': return $this->addConsultant();
                break;

            case 'userDelete': return $this->userDelete();
                break;

            case 'getAnalytics': return $this->getAnalytics();
                break;

            default: $this->response('error', '', 'Unknown method');
            break;
        }
    }

    private function getClient(){

        $html   = \Yii::$app->request->post('html');
        $id     = \Yii::$app->request->post('id');

        $user = Users::getUser($id, Users::$users_labels['account_type']['client']); // [1] - client | [2]  consultant | [3] - administrator
        if($user){
            if($html){
            $html = '<div class="top-block">
                        <div class="close_popup_info" onclick="closemodal();"></div>
                        <div class="client_name">'.$user->full_name.'</div>
                        <div class="client_id">ID '.$user->id.'</div>
                        <div class="client_photo">'.Users::theAvatar($user->id).'</div>
                    </div>
                    <div class="bottom-block">
                        <div class="client_attributes">
                            <div class="client_balance"><div class="balance">'.$user->balance.' Psy</div><div class="balance_title">баланс</div></div>
                            <div class="client_usedtime"><div class="usedtime">'.Users::ConsultationTimeFormat($user->consultation_time).'</div><div class="usedtime_title">время консультаций</div></div>
                        </div>
                        <div class="line"></div>
                        <div class="client_problem">
                            <div class="title">Проблема</div>
                            <div class="value">'.$user->conversation_theme.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="client_age">
                            <div class="title">Возраст</div>
                            <div class="value">'.$user->age.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="client_lang">
                            <div class="title">Язык</div>
                            <div class="value">Русский</div>
                        </div>
                        <div class="line"></div>
                        <div class="client_phone">
                            <div class="title">Телефон</div>
                            <div class="value">'.$user->phone.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="submit_btn"><input type="button" class="purple" value ="Отправить сообщение"></div>
                        <div class="client_delete_link"><a href="#">Удалить клиента</a></div>
                    </div>';
                return $this->response('success', $html);
            }else{
                return $this->response('success', $user);
            }
        }else{
            return $this->response('error', '', 'user not found');
        }

    }

    private function getConsultant(){

        $html   = \Yii::$app->request->post('html');
        $id     = \Yii::$app->request->post('id');

        $user = Users::getUser($id, Users::$users_labels['account_type']['consultant']); // [1] - client | [2]  consultant | [3] - administrator
        if($user){
            if($html){
            $html = '<div class="top-block">
                        <div class="close_popup_info" onclick="closemodal();"></div>
                        <div class="consultant_name">'.$user->full_name.'</div>
                        <div class="consultant_photo">'.Users::theAvatar($user->id).'</div>
                    </div>
                    <div class="bottom-block">
                        <div class="consultant_attributes">
                            <div class="consultant_balance"><div class="balance">'.$user->balance.' Psy</div><div class="balance_title">баланс</div></div>
                            <div class="consultant_usedtime"><div class="usedtime">'.Users::ConsultationTimeFormat($user->consultation_time).'</div><div class="usedtime_title">время консультаций</div></div>
                            <div class="consultant_rating"><div class="rating">'.$user->rating.'</div><div class="rating_title">Рейтинг</div></div>
                        </div>
                        <div class="line"></div>
                        <div class="consultant_phone">
                            <div class="title">Телефон</div>
                            <div class="value">'.$user->phone.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="consultant_specialist">
                            <div class="title">Специальность / Стаж</div>
                            <div class="value">'.$user->education.' / '.$user->experience.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="consultant_lang">
                            <div class="title">Язык</div>
                            <div class="value">'.$user->languages.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="consultant_inn">
                            <div class="title">ИИН/ИНН</div>
                            <div class="value">'.$user->tin.'</div>
                        </div>
                        <div class="line"></div>
                        <div class="submit_btn"><input type="button" class="purple" value ="Отправить сообщение"></div>
                        <div class="consultant_delete_link"><a href="#">Удалить консультанта</a></div>
                    </div>';
                return $this->response('success', $html);
            }else{
                return $this->response('success', $user);
            }
        }else{
            return $this->response('error', '', 'user not found');
        }

    }

    private function sendMessage($send_type = ''){

        $hash = \Yii::$app->request->post('hash');
        $message = \Yii::$app->request->post('message');
        $html = (\Yii::$app->request->post('html') ? \Yii::$app->request->post('html') : false);

        $current_user = Users::getUserByHash($hash);
        if(!$current_user) $this->response('error', '', 'hash is incorrect');

        if($send_type == 'user'){
            $user_id = (int) \Yii::$app->request->post('user_id');
            if($current_user->id == $user_id) $this->response('error', '', 'Вы не можете отправлять сообщение самому себе');
            $conversation = Conversations::getConversationByUsersId($user_id, $current_user->id, true);

            if(!$conversation){
                // Создаём новый диалог
                $conversations = new Conversations();
                $conversation = $conversations->createConversation($current_user->id, $user_id);
                if(!$conversation){
                    $this->response('error','','Не удалось создать диалог');
                    exit;
                }
                $conversation_id = $conversations->id; // последний созданый диалог

                // добавляем учасников в новый диалог
                $usersConversation = new UsersConversation();
                $usersConversation->addUserToConversation($conversation_id, $current_user->id); // Кто отправляет

                $usersConversation = new UsersConversation();
                $usersConversation->addUserToConversation($conversation_id, $user_id); // Кому отправляет

            }else{
                $conversation_id = $conversation[0]['conversation_id'];
            }
        }
        elseif($send_type == 'conversation'){
            $conversation_id = (int) \Yii::$app->request->post('conversation_id');
            $conversation = Conversations::findOne(['id' => $conversation_id]);

            if(!$conversation) $this->response('error', '', 'Unknown conversation');
        }


        // Отправляем новое сообщение
        $messages = new Messages();
        $result = $messages->saveMessage($current_user->id, $conversation_id, $message);

        if($result){
            if($html){
                $direction_class = ($current_user->id == $messages->user_id ? 'im' : 'from');
                $messages->date = time() + 3600; // Костыль (time() почемуто возвращает на час меньше от текущего)
                $result = Messages::createHtml(['block' => 'message', 'data' => $messages, 'direction_class' => $direction_class]);
            }

            $this->response('success', $result);
        }else{
            $this->response('error', '', 'Не удалось отправить сообщение');
        }

    }

    private function getMessagesFromConversation(){
        $hash = \Yii::$app->request->post('hash');
        $conversation_id = \Yii::$app->request->post('conversation_id');
        $html = \Yii::$app->request->post('html');

        $current_user = Users::getUserByHash($hash);
        if(!$current_user) $this->response('error', '', 'hash is incorrect');
        $messages = Messages::getMessagesFromConversation($conversation_id);

        if(!$messages) $this->response('error', '', 'Unknown conversation '.$conversation_id);

        if($html){
            $html = '';
            foreach ($messages as $message) {
                $direction_class = ($current_user->id == $message->user_id ? 'im' : 'from');
                $html .= Messages::createHtml(['block' => 'message', 'data' => $message, 'direction_class' => $direction_class]);
            }

            $this->response('success', $html);
        }

        $this->response('success', $messages);


    }

    private function getUnreadMessages(){
        $hash = \Yii::$app->request->post('hash');
        $html = (\Yii::$app->request->post('html') ? \Yii::$app->request->post('html') : false);

        $current_user = Users::getUserByHash($hash);

        if(!$current_user) $this->response('error', '', 'hash is incorrect');

        $messages = Messages::getUserUnreadMessages($current_user->id);

        if($html){
            $html = '';
            foreach ($messages as $message) {
                $direction_class = ($current_user->id == $message->user_id ? 'im' : 'from');
                $html .= Messages::createHtml(['block' => 'message', 'data' => $message, 'direction_class' => $direction_class]);
            }

            $this->response('success', $html);
        }

        $this->response('success', $messages);
    }

    private function setMessagesToRead(){
        $conversation_id = \Yii::$app->request->post('conversation_id');
        $hash = \Yii::$app->request->post('hash');
        $current_user = Users::getUserByHash($hash);
        if(!$current_user) $this->response('error', '', 'hash is incorrect');
        $result = Messages::setMessagesToRead($conversation_id, $current_user->id);
        if($result){
            $this->response('success');
        }
        $this->response('error', '', 'Unknown error');
    }

    private function addConsultant(){
        $request = Yii::$app->request->post();
        $hash = $request['hash'];
        $current_user = Users::getUserByHash($hash);
        $password = (isset($request['password']) && $request['password']!=''  ? $request['password'] : Users::stringGenerator());

        if(!$current_user || $current_user->account_type != Users::$users_labels['account_type']['administrator']) $this->response('error', '', 'hash is incorrect or you is not administrator');

        $users = new Users();

        $users->username = $request['phone'];
        $users->email;
        $users->password = Users::encodePassword('demo');
        $users->full_name = $request['full_name'];
        $users->age = $request['age'];
        $users->phone = $request['phone'];
        $users->education = $request['education'];
        $users->experience = $request['experience'];
        $users->sex = $request['sex'];
        $users->languages = '1';#$request['languages'];
        $users->tin = $request['tin'];
        $users->description = $request['description'];
        $users->account_type = Users::$users_labels['account_type']['consultant'];

        $save = $users->save();


        if($save){
            $model = new UploadForm();
            $certificates = UploadedFile::getInstances($model, 'certificates');
            $avatar = UploadedFile::getInstance($model, 'avatar');
            if (Yii::$app->request->isPost && $certificates || $avatar) {
                $model->certificates = $certificates;
                $model->avatar = $avatar;
                if ($model->upload($users->id)) {
                    $this->response('success', $users, 'Фотографии загружены! Пароль пользователя: '.$password);
                    return;
                }else{
                    $this->response('error', '', 'Консультант добавлен но фото не были загружены');
                }
            }

            $this->response('success', $users, 'Пароль пользователя: '.$password);
        }

        $this->response('error', '', 'Неизвестная ошибка');
    }

    private function userDelete(){
        $request = Yii::$app->request->post();
        $hash = $request['hash'];
        $user_id = (int) $request['user_id'];
        $current_user = Users::getUserByHash($hash);

        if(!$current_user || $current_user->account_type != Users::$users_labels['account_type']['administrator']) $this->response('error', '', 'hash is incorrect or you is not administrator');

        $user = Users::findOne(['id' => $user_id]);

        if(!$user) $this->response('error','', 'Пользователь с ID не найден');
        if(!$user->delete()) $this->response('error', '', 'Не могу удалить пользователя, обратитесь к администратору');

        $this->response('success');

    }

    private function getAnalytics(){
        return \Yii::$app->request->post('subaction');
        $analytics = Analytics::findAll();
        return json_encode($analytics);
    }

    private function response($status = '', $result = '', $message = ''){
        $json_response = array('status' => $status, 'result' => $result, 'message' => $message);
        echo json_encode($json_response);
        exit;
    }

}