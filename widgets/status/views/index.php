<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 11.03.2018
 * Time: 15:41
 *
 * @var $this yii\web\View
 * @var $model \fedornabilkin\binds\models\base\BindModel
 * @var $btns array
 */

use yii\helpers\Html;

?>

<div class="statuses btn-group btn-group-sm">
<?php foreach ($btns as $index => $btn):
    $active = $index == $model->uids->status ? 'active' : '';
    ?>
    <?= Html::button($btn, [
    'class' => 'change btn btn-default ' . $active,
    'data-uid' => $model->uid,
    'data-status' => $index,
    'data-request' => "ajax",
    'data-handler' => "StatusSar",
    'data-url' => "/binds/status/save",
])?>
<?php endforeach?>
</div>
