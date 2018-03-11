<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 08.03.2018
 * Time: 23:30
 *
 * @var $this yii\web\View
 * @var $node fedornabilkin\binds\models\Catalog
 * @var $form \kartik\form\ActiveForm
 */

?>
<div class="row">
    <div class="col-xs-12 col-sm-2">
        <?= $form->field($node, 'uid')->input('text', ['readonly' => 'readonly']);?>
    </div>
    <div class="col-xs-12 col-sm-5">
        <?= $form->field($node, 'nickname')->input('text');?>
    </div>
    <div class="col-xs-12 col-sm-5">
        <?= $form->field($node, 'alias')->input('text');?>
    </div>
</div>