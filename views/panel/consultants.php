<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 18.06.2017
 * Time: 22:08
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Консультанты';
$this->registerCssFile('@web/assets/css/consultants.css');
?>
<div class="title">Консультанты</div>

<div class="consultants_sort_buttons">
    <input type="button" class="whitebtn" data-status="all" value="Все" />
    <input type="button" class="whitebtn" data-status="online" value="Онлайн" />
    <input type="button" class="whitebtn" data-status="busy" value="Занят" />
    <?php echo Html::button('<i class="add_consultant"><div class="vertical"></div><div class="horisontal"></div></i>Добавить нового консультанта', ['class' => 'whitebtn add_consultant_btn', 'onclick' => 'location.href="'.Url::toRoute(['panel/consultants', 'subaction' => 'add']).'"']);?>
</div>

<div class="consultants_list">
    <?php
    if($consultants){
        foreach($consultants as $consultant){
            ?>
            <div class="consultant-block online">
                <div class="status <?php echo $consultant->status; ?>"></div>
                <div class="photo"><?php echo $this->context->theAvatar($consultant->id); ?></div>
                <div class="full_name"><?php echo $consultant->full_name; ?></div>
                <div class="send_message"><a href="javascript://" class="popup-messagelink" data-id="<?php echo $consultant->id; ?>"><i class="mail"></i></a></div>
                <div class="view_info"><a href="javascript://" class="popup-userinfo" data-account_type="consultant" data-id="<?php echo $consultant->id; ?>"><i class="info"></i></a></div>
            </div>
            <?php
        }
    }else{
        echo 'Нету консультантов';
    }

    ?>
</div>