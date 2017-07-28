<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 18.06.2017
 * Time: 22:08
 */


$this->title = 'Контрольная панель';
$this->registerCssFile('@web/assets/css/control_panel.css');
$this->registerCssFile('@web/assets/c3/c3.min.css');
$this->registerJsFile('@web/assets/c3/c3.min.js');
$this->registerJsFile('http://c3js.org/js/d3-3.5.6.min-77adef17.js');

?>
<div class="title">Контрольная панель</div>
<div class="balance">Баланс сервиса 1,000,000 PSY</div>
<div class="clear"></div>
<div class="send_push"><input type="button" value="Отправить PUSH уведомление" id="send_push_open_popup"></div>
<div class="clear"></div>
<div class="statistic">
    <div class="user_online"><div class="title">Клиенты онлайн</div><div class="value"><?php echo $online[$users_labels['account_type']['client']]; ?></div></div>
    <div class="consultants_online"><div class="title">Консультанты онлайн</div><div class="value"><?php echo $online[$users_labels['account_type']['consultant']]; ?></div></div>
    <div class="top_users"><div class="title">Лучшие клиенты</div>
        <div class="value">
            <ul>
                <?php foreach ($topUsers['clients'] as $client): ?>
                    <li><a href="javascript://" class="popup-userinfo" data-account_type="client" data-id="<?php echo $client->id; ?>"><?php echo $client->full_name; ?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="top_consultants"><div class="title">Топ 5 консультантов</div>
        <div class="value">
            <ul>
                <?php foreach ($topUsers['consultants'] as $consultant): ?>
                    <li><a href="javascript://" class="popup-userinfo" data-account_type="consultant" data-id="<?php echo $consultant->id; ?>"><?php echo $consultant->full_name; ?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>
<div class="clear"></div>
<div class="time_statistic">
    <div class="title">Время консультаций</div>
    <div class="graph_tabs"><input type="button" class="whitebtn" id="chartToDays" value="По дням" ><input type="button" class="whitebtn" value="По неделям" ><input type="button" class="whitebtn" value="По месяцам" ></div>
    <div class="graph" id="chart"></div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){

        var chart = c3.generate({

            bindto: '#chart',

            data: {
                x: 'x',
                columns: [
                    ['x', '2017-02-01', '2017-03-01', '2017-04-01', '2017-05-01', '2017-06-01'],
                    ['data1', 30, 200, 100, 400, 150, 250]
                ]
            },

            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%m.%d'
                    }
                }
            },

            padding: {
                right: 20
            },

            color: {
                pattern: ['#F25D9C']
            },

            point: {
                r: 7,
                focus: {
                    expand: {
                        r: 9
                    }
                }
            }

        });

        $('#chartToDays').on('click', function(){
            chart.load({
                columns: [
                    ['x', '2017-02-01', '2018-03-01', '2019-04-01', '2020-05-01', '2021-06-01'],
                    ['data1', 180, 20, 150, 1400, 20],
                ],
                axis: {
                    x: {
                        type: 'timeseries',
                        tick: {
                            format: '%Y.%d'
                        }
                    }
                },

            });
        });

    });

</script>
