<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Страница входа';
?>
<div class="border">
    <div class="login-form">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{input}<div class='error'>{error}</div>",
            ],
        ]); ?>



            <div class="login-title"><font>Вход в админ панель</font></div>
            <div class="login-field_title"><font>Логин</font></div>
            <div class="login-field"><?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?></div>
            <div class="login-field_title"><font>Пароль</font></div>
            <div class="login-field"> <?= $form->field($model, 'password')->passwordInput() ?></div>
            <div class="login-submit-button"><?= Html::submitInput('Войти', ['name' => 'login-button']) ?></div>
            <div class="login-restorepwd"><a href="#">Забыли пароль?</a></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>