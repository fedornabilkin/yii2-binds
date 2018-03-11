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

use yii\helpers\Html;

?>
<h4> Seo информация </h4>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <?= Html::activeLabel($model, 'title');?>
        <?= Html::activeInput('text', $model, 'title', ['class'=>'form-control'])?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?= Html::activeLabel($model, 'alias');?>
        <?= Html::activeInput('text', $model, 'alias', ['class'=>'form-control'])?>
        <?= Html::error($model, 'alias', ['style' => 'color:red'])?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?= Html::activeLabel($model, 'keywords');?>
        <?= Html::activeInput('text', $model, 'keywords', ['class'=>'form-control'])?>
    </div>
    <div class="col-xs-12 col-sm-6">
        <?= Html::activeLabel($model, 'h1');?>
        <?= Html::activeInput('text', $model, 'h1', ['class'=>'form-control'])?>
    </div>
    <div class="col-xs-12">
        <?= Html::activeLabel($model, 'description');?>
        <?= Html::activeTextarea($model, 'description', ['class'=>'form-control'])?>
    </div>
</div>