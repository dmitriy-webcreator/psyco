<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 19.06.2017
 * Time: 0:12
 */
$this->title = 'Регистрация';
?>
<br/>
<br/>
<h1>Registration</h1>
<?php $form = \yii\widgets\ActiveForm::begin(['class'=>'form-horizontal']); ?>

<?php echo $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
<?php echo $form->field($model, 'password')->passwordInput(); ?>
<input type="submit" class="btr brn-primary" value="Зарегаться" />

<?php \yii\widgets\ActiveForm::end(); ?>

