<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Клиенты';
$this->registerCssFile('@web/assets/css/clients.css');
?>

<div class="title">Клиенты</div>

<div class="clients_sort_buttons">
    <?= Html::a('Все', ['panel/clients'], ['class' => 'whitebtn']); ?>
    <?= Html::a('Онлайн', ['panel/clients', 'subaction' => 'online'], ['class' => 'whitebtn']); ?>
    <?= Html::a('Рейтинг', ['panel/clients', 'subaction' => 'rating'], ['class' => 'whitebtn']); ?>
    <input type="button" class="whitebtn" value="Вывести список клиентов в XLS" />
</div>
<div class="clients_list_table">
    <table cellspacing="0" cellpadding="0">
        <tr>
            <th style="width: 150px; height: 30px;" >Имя</th>
            <th style="width: 110px; height: 30px;" >ID</th>
            <th style="width: 150px; height: 30px;" >Тема беседы</th>
            <th style="width: 163px; height: 30px;" >Общее время</th>
            <th style="width: 130px; height: 30px;" >Баланс PSY</th>
            <th style="width: 162px; height: 30px;" >Потрачено PSY</th>
            <th style="width: 105px; height: 30px;" >Действие</th>
        </tr>
        <?php
            if(!empty($clients)){
                foreach ($clients as $client) {
                    ?>
                    <tr class="userID<?php echo $client->id; ?>">
                        <td class="<?php echo $this->context->getStatus($client->status); ?>"><div class="status"></div><?php echo ($client->full_name ? $client->full_name : $client->username) ; ?></td>
                        <td><?php echo $client->id; ?></td>
                        <td><?php echo $client->conversation_theme; ?></td>
                        <td><?php echo $client->ConsultationTimeFormat($client->consultation_time);?></td>
                        <td><?php echo $client->balance; ?></td>
                        <td><?php echo $client->balance_spent; ?></td>
                        <td>
                            <a href="javascript://" class="popup-messagelink" data-id="<?php echo $client->id; ?>"><i class="mail mail-small"></i></a>
                            <a href="javascript://" class="popup-userinfo" data-account_type="client" data-id="<?php echo $client->id; ?>"><i class="info info-small"></i></a>
                            <a href="javascript://" class="popup-delete" data-id="<?php echo $client->id; ?>"><i class="del del-small"></i></a>
                        </td>
                    </tr>
                    <?php
                }
            }else{
                echo '<tr><td>Клиентов не найдено</td></tr>';
            }
        ?>
    </table>
</div>