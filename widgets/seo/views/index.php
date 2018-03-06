<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 04.03.2018
 * Time: 0:38
 *
 * @var $this yii\web\View
 * @var $model \fedornabilkin\binds\models\Seo
 */
?>
<hr>
<h4> Seo информация </h4>
<?= \yii\helpers\Html::activeLabel($model, 'title');?>
<?= \yii\helpers\Html::activeInput('text', $model, 'title', ['class'=>'form-control'])?>

<?= \yii\helpers\Html::activeLabel($model, 'alias');?>
<?= \yii\helpers\Html::activeInput('text', $model, 'alias', ['class'=>'form-control'])?>
<?=\yii\helpers\Html::error($model, 'alias', ['style' => 'color:red'])?>

<?= \yii\helpers\Html::activeLabel($model, 'h1');?>
<?= \yii\helpers\Html::activeInput('text', $model, 'h1', ['class'=>'form-control'])?>

<?= \yii\helpers\Html::activeLabel($model, 'keywords');?>
<?= \yii\helpers\Html::activeInput('text', $model, 'keywords', ['class'=>'form-control'])?>

<?= \yii\helpers\Html::activeLabel($model, 'description');?>
<?= \yii\helpers\Html::activeTextarea($model, 'description', ['class'=>'form-control'])?>
