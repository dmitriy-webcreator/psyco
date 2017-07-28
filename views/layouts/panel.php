<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);

$current_user = \Yii::$app->user->identity;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo Yii::$app->language ?>">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <?php echo Html::csrfMetaTags() ?>
    <title><?php echo Html::encode($this->title) ?></title>
    <script src="<?php echo Url::to('@web/assets/js/jquery.js'); ?>"></script>
    <script src="<?php echo Url::to('@web/assets/js/jquery.form.js'); ?>"></script>
    <script src="<?php echo Url::to('@web/assets/js/scrollbar.jquery.js'); ?>"></script>
    <script type="text/javascript">
        var hash = '<?php echo $this->context->user_hash(); ?>';
        var ajaxUrl = '<?php echo $this->context->ajaxUrl; ?>';
        var updateMessagePause = false;
    </script>
    <link href="<?php echo Url::to('@web/assets/css/scrollbar.jquery.css'); ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo Url::to('@web/favicon.png'); ?>" type="image/png">
    <?php $this->head() ?>
</head>
<body class="default-skin">
<?php $this->beginBody() ?>
<div class="top-background"></div>
<div class="border">
    <!-- TOP MENU -->
    <div class="top-menu">
        <div class="logo"><?php echo Html::img('@web/assets/img/logos/Icon-55.png');?><font>Psyco</font></div>
        <div class="separator"></div>
        <div class="cpanel"><a href="<?php echo Url::toRoute(['panel/']); ?>"><?php echo Html::img('@web/assets/svg/contr_panel.svg'); ?><div>Контр. панель</div></a></div>
        <div class="separator"></div>
        <div class="clients"><a href="<?php echo Url::toRoute(['panel/clients']); ?>"><?php echo Html::img('@web/assets/svg/clients.svg'); ?><div>Клиенты</div></a></div>
        <div class="separator"></div>
        <div class="consultants"><a href="<?php echo Url::toRoute(['panel/consultants']); ?>"><?php echo Html::img('@web/assets/svg/consultants.svg'); ?><div>Консультанты</div></a></div>
        <div class="separator"></div>
        <div class="chats"><a href="<?php echo Url::toRoute(['panel/messages']); ?>"><?php echo Html::img('@web/assets/svg/conversations.svg'); ?><div>Чаты</div></a></div>
        <div class="separator"></div>
        <div class="profile"><div class="photo"><?php echo $this->context->theAvatar(); ?></div><div class="full_name"><?php echo (isset($current_user->full_name) ? $current_user->full_name : ''); ?></div><div class="logout_link"><a href="<?php echo Url::toRoute(['account/logout']); ?>">Выйти</a></div></div>
    </div>
    <!-- end TOP MENU -->
    <div class="content"><?php echo $content; ?></div>
</div>

<div class="overlay"></div>


<!-- Удалить клиента POPUP -->
<div class="popup-client_delete">
</div>
<!-- end Удалить клиента POPUP -->

<!-- popup SEND MESSAGE -->
<div class="popup-send_message">

</div>
<!-- end popup SEND MESSAGE -->

<!-- popup SEND PUSH MESSAGE -->

<div class="popup-send_push_message">
    <div class="title">Сообщение</div><div class="close" onclick="closemodal();" ></div>
    <div class="clear"></div>
    <div class="message"><textarea id="send_message" placeholder="Введите сообщение" ></textarea></div>
    <div class="clear"></div>
    <div class="options">
        <div class="title">Разослать</div>
        <div class="list">
            <ul>
                <li><input type="radio" id="all" name="options_list" value="all" class="radio" checked><label for="all">Всем</label></li>
                <li><input type="radio" id="forconsultants" name="options_list" value="forconsultants" class="radio" ><label for="forconsultants">Консультантам</label></li>
                <li><input type="radio" id="forclients" name="options_list" value="forclients" class="radio" ><label for="forclients">Клиентам</label></li>
            </ul>
        </div>
    </div>

    <div class="clear"></div>
    <input type="button" class="form_cancel_btn" value="Отмена" onclick="closemodal();"><input type="button" class="form_submit_btn purple" value="Отправить" >
</div>
<!-- end popup SEND PUSH MESSAGE -->

<!-- Информация о клиенте POPUP -->
<div class="popup-client_info">
</div>
<!-- end Информация о клиенте POPUP -->

<!-- Информация о консультанте POPUP -->
<div class="popup-consultant_info">
</div>
<!-- end Информация о консультанте POPUP -->


<script type="text/javascript" src="<?php echo Url::to('@web/assets/js/notify.min.js'); ?>"></script>
<script type="text/JavaScript">
    function closemodal(){
        jQuery('div.border').removeClass('blur');
        jQuery('div[class ^= "popup-"]').hide(); // скрываем popup
        jQuery('.overlay').hide(); // скрываем подложку
    }
    jQuery(document).ready(function($){

        $(".messages").customScrollbar();

        $('#send_push_open_popup').on('click', function(){
            $('.popup-send_push_message').css('opacity', '1');
            $('.popup-send_push_message').show();
            $('.overlay').show();
            $('div.border').addClass('blur');
        });

        $('a.popup-messagelink').click(function(){
            var id = $(this).data('id');
            var action = 'sendUserMessage';
            html = '<form class="sendMessage"><div class="title">Сообщение</div><div class="close" onclick="closemodal();" ></div><div class="clear"></div><div class="message"><textarea id="send_message" placeholder="Введите сообщение" ></textarea></div><div class="clear"></div><input type="button" class="form_cancel_btn" value="Отмена" onclick="closemodal();"><input type="submit" class="form_submit_btn purple" id="send_message_submit" value="Отправить" ></form>';
            $('.popup-send_message').html(html);
            $('.popup-send_message').css('opacity', '1');
            $('.popup-send_message').show();
            $('.overlay').show();
            $('div.border').addClass('blur');

            $('.sendMessage').on('submit', function(e){
                var message = $('#send_message').val();
                if(message.length <= '3'){
                    $.notify('Введите текст сообщения', 'warn');
                    return false;
                }

                $.ajax({
                    method: "POST",
                    url: ajaxUrl,
                    data: { action: action, user_id: id, hash: hash, message: message }
                }).done(function( responseJson ) {
                        var response;
                        response = $.parseJSON(responseJson);
                        if(response.status == 'success'){
                            closemodal();
                            $.notify('Отправлено', response.status);
                        }else{
                            $.notify('Неожиданный ответ от сервера:\r\n'+response.message, response.status);
                        }

                    });
                return false;
            });
        });

        $('a.popup-delete').on('click', function(e){
//            e.preventDefault();
            var id = $(this).data('id');
            var action = 'userDelete';
            var html = '<div class="title">Вы действительно хотите удалить этого клиента?</div><input type="button" class="purple submit_delete" value="Да" /><input type="button" class="purple close_popup_delete" value="Нет"  onclick="closemodal();" />';

            $('.popup-client_delete').html(html);
            $('.popup-client_delete').css('opacity', '1');
            $('.popup-client_delete').show();
            $('.overlay').show();
            $('div.border').addClass('blur');
            $('.popup-client_delete').find('.submit_delete').on('click', function(){
                $.ajax({
                    method: "POST",
                    url: ajaxUrl,
                    data: { action: action, user_id: id, hash: hash}
                }).done(function( responseJson ) {
                    var response;
                    response = $.parseJSON(responseJson);
                    if(response.status == 'success'){
                        closemodal();
                        $.notify('Удалён', response.status);
                        $('tr.userID'+id).remove();
                    }else{
                        closemodal();
                        $.notify('Неожиданный ответ от сервера:\r\n'+response.message, response.status);
                    }

                });
            });
        });

        $('a.popup-userinfo').on('click', function(){
            var id = $(this).data('id');
            var account_type = $(this).data('account_type');
            var action = (account_type == 'client') ? 'getClient' : 'getConsultant';

            $.ajax({
                method: "POST",
                url: ajaxUrl,
                data: { action: action, id: id, html: true }
            })
                .done(function( responseJson ) {
                    var response;
                    response = $.parseJSON(responseJson);
                    if(response.status == 'success'){
                        $('.popup-'+account_type+'_info').html(response.result);
                        $('.popup-'+account_type+'_info').css('opacity', '1');
                        $('.popup-'+account_type+'_info').show();
                        $('.overlay').show();
                        $('div.border').addClass('blur');
                    }else{
                        alert('Неожиданный ответ от сервера\r\nСтатус: '+response.status+'\r\nСообщение: '+response.message);
                    }

                });


        });

        $(document).mouseup(function (e){ // событие клика по веб-документу
            var div = $('div[class ^= "popup-"]'); // тут указываем ID элемента
            if (!div.is(e.target) // если клик был не по нашему блоку
                && div.has(e.target).length === 0) { // и не по его дочерним элементам
                closemodal();
            }
        });

        $('.clients_sort_buttons input').on('click', function(){
            var sortby = $(this).data('sort');

            if(sortby == 'undefined') return; // ссылка для скачивания XLS

            if(sortby == 'all'){ // показать всех
                $('td').closest('tr').show();
            }

            if(sortby == 'online'){ // сортировка клиентов по онлайну
                $('td').closest('tr').hide();
                $('td.online').closest('tr').show();
            }
        });





    });
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();