<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 28.06.2017
 * Time: 18:35
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Новый консультант';
$this->registerCssFile('@web/assets/css/consultants.css');
$this->registerJsFile('@web/assets/js/consultants.js');

?>
<?php   $form = ActiveForm::begin(['action' => $this->context->ajaxUrl, 'id'=> 'addNewConsultantForm', 'options' => ['enctype' => 'multipart/form-data' ]]); ?>
<form action="<?php echo $this->context->ajaxUrl; ?>" method="POST" style="" id="addNewConsultantForm">
    <div class="content_add_consultant" style="">
        <div class="title"><a href="<?php echo Url::toRoute(['panel/consultants']); ?>"><?php echo Html::img('@web/assets/svg/back.svg'); ?>Новый консультант</a></div>
        <div class="block_1">
            <div class="title"><label for="full_name">Ф.И.О.</label></div>
            <div class="value"><input name="full_name" id="full_name" placeholder="Введите Ф.И.О." type="text"></div>

            <div class="title"><label for="phone">Номер телефона</label></div>
            <div class="value"><input name="phone" id="phone" placeholder="Введите номер телефона" type="text"></div>

            <div class="title"><label for="age">Возраст</label></div>
            <div class="value"><input name="age" id="age" placeholder="Введите возраст" type="text"></div>

            <div class="title">Пол</div>
            <div class="value">
                <input id="sex_male" class="radio" name="sex" value="male" selected="" type="radio"><label for="sex_male">Муж.</label>
                <input id="sex_female" class="radio" name="sex" value="female" type="radio"><label for="sex_female">Жен.</label>
            </div>
        </div>

        <div class="block_2">
            <div class="title"><label for="education">Образование</label></div>
            <div class="value"><input name="education" id="education" placeholder="Введите специальность" type="text"></div>

            <div class="title"><label for="experience">Стаж</label></div>
            <div class="value"><input name="experience" id="experience" placeholder="Введите стаж" type="text"></div>

            <div class="title"><label for="tin">ИНН</label></div>
            <div class="value"><input name="tin" id="tin" placeholder="Введите ИНН" type="text"></div>

            <div class="title">Владение языками</div>
            <div class="value">
                <input id="languages_ru" class="checkbox" name="languages[]" value="ru" type="checkbox"><label for="languages_ru">Русский</label>
                <input id="languages_kz" class="checkbox" name="languages[]" value="kz" type="checkbox"><label for="languages_kz">Казахский</label>
            </div>
        </div>

        <div class="block_3">
            <div class="title"><label for="description">Описание</label></div>
            <div class="value"><textarea name="description" id="description" placeholder="Описание"></textarea></div>
            <div class="attachment" style="margin-right: 65px;">
                <div class="title photo_title">Фото</div>
                <div class="value photo_value"><?= $form->field($model, 'avatar')->label(null,['class'=>'btn btn-primary'])->fileInput([]) ?><input id="photo_styling" value="Прикрепить фото" type="button"></div>
            </div>
            <div class="attachment">
                <div class="title certificates_title">Сертификаты</div>
                <div class="value certificates_value"><?= $form->field($model, 'certificates[]')->fileInput(['id'=>'certificates_btn', 'multiple' => true, 'accept' => 'image/*']) ?><input id="certificates_styling" value="Прикрепить сертификаты" type="button"></div>
            </div>
            <div class="certificates_list"><ul></ul></div>
        </div>
        <div class="add_consultant_submit"><input name="submit" class="purple " value="Добавить консультанта" type="submit"></div>
        <div class="link_back"><a href="<?php echo Url::toRoute(['panel/consultants']); ?>">&lt; Вернуться назад</a></div>
    </div>
    <input type="hidden" name="action" value="addConsultant" />
    <input type="hidden" name="hash" value="<?php echo $this->context->user_hash(); ?>" />

</form>
<?php ActiveForm::end(); ?>

