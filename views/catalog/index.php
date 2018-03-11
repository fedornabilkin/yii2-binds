<?php
/**
 * @var $this yii\web\View
 * @var $query \yii\db\ActiveQuery
 */

use kartik\tree\Module;
use kartik\tree\TreeView;

$this->title = 'Управление каталогом';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= $this->title?></h1>

<?php
echo TreeView::widget([
    'query' => $query,
    'nodeAddlViews' => [
        Module::VIEW_PART_2 => '@fedornabilkin/binds/views/catalog/tree/_treePart2'
    ],
    'headingOptions' => ['label' => 'Каталог'],
    'isAdmin' => true,
    'displayValue' => 2,
    'softDelete' => false,
    'cacheSettings' => ['enableCache' => true],
//        'showIDAttribute' => false,
    'showInactive' => true,
    'rootOptions' => [
        'label' => '',
        'class' => 'hide'
    ],
]);

?>
