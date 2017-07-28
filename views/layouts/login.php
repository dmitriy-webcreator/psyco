<?php
/**
 * Created by PhpStorm.
 * User: Дмитрий
 * Date: 17.06.2017
 * Time: 19:53
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo Html::encode($this->title) ?></title>
    <meta charset="<?php echo Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo Html::csrfMetaTags() ?>
    <link type="text/css" rel="stylesheet" href="<?php echo Url::to('@web/assets/css/style.css');?>" />
    <link type="text/css" rel="stylesheet" href="<?php echo Url::to('@web/assets/css/login.css');?>" />
    <?php $this->head();?>
</head>
<body>
<?php echo $content; ?>
</body>
</html>
