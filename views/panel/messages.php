<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 18.06.2017
 * Time: 22:08
 */


$this->title = 'Чаты';
$this->registerCssFile('@web/assets/css/conversations.css');
$this->registerJsFile('@web/assets/js/conversations.js');

//$this->context->debug($conversations);
?>
    <?php if($conversations):?>
    <div class="list_box" >
        <div id="circleG">
            <div id="circleG_1" class="circleG"></div>
            <div id="circleG_2" class="circleG"></div>
            <div id="circleG_3" class="circleG"></div>
        </div>
        <div class="title">Чаты</div>
        <div class="tabs">
            <?php foreach($conversations as $conversation){ ?>
                <div class="tab" data-conversation_id="<?php echo $conversation->conversation_id?>">
                    <a href="javascript://" data-conversation_id="<?php echo $conversation->conversation_id?>">
                        <div class="photo"><?php echo $this->context->theAvatar($conversation->user_id);?></div>
                        <div class="name"><?php echo $conversation->full_name; ?></div>
                        <div class="user_type"><?php echo $this->context->getAccountType($conversation->account_type);?></div>
                    </a>
                </div>
            <?php }?>
        </div>

    </div>
    <div class="chat_box">
        <?php foreach($conversations as $conversation): ?>
            <div class="conversation_id-<?php echo $conversation->conversation_id; ?>" data-full_name="<?php echo $conversation->full_name; ?>" >
                <div class="title"><?php echo $this->context->getAccountType($conversation->account_type);?>: <?php echo $conversation->full_name; ?></div>
                <div class="messages default-skin scrollable">
                </div>
            </div>
        <?php endforeach; ?>

        <form id="sendMessage" data-conversation_id="">
            <div class="toolbar">
                <input type="text" name="send_text" id="message_text" placeholder="Сообщение" /> <input type="submit" value="Отправить" />
            </div>
        </form>
    </div>
    <?php else:?>
        <div class="list_box" >
            <div class="title">Чаты</div>
        </div>
        <div class="chat_box" >
            <div class="nochats_text">Пока у Вас нет ни одного чата.<br><br> Напишите любому пользователю сервиса PSYCO чтобы добавить чаты на данную страницу.</div>
            <div class="mail_buttons"><input value="Написать консультанту" class="whitebtn" type="button" onclick="location.href='consultants.html'" /><input value="Написать клиенту" class="whitebtn" type="button" onclick="location.href='clients.html'" /></div>
        </div>
    <?php endif;?>