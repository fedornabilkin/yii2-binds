<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 09.03.2018
 * Time: 16:07
 */

namespace fedornabilkin\binds\widgets\binds;

use fedornabilkin\binds\models\Catalog;
use kartik\tree\TreeViewInput;
use yii\base\Widget;

/**
 * Class BindsWidget
 * @package fedornabilkin\binds\widgets\binds
 */
class BindsWidget extends Widget
{
    public $model;
    private $_binds;
    private $_idsIn;

    public function run()
    {
        $tree = $this->model->tree;
        $this->_binds = array_column($this->model->bindsArray, 'uid_bind');
        $this->_idsIn = implode(',', $this->_binds);

        if (!empty($tree) && count($tree['nicknames'])) {
            foreach ($tree['nicknames'] as $nn => $pars) {
                echo $this->_getTree($nn, $pars);
            }
        }
    }

    private function _getTree($nn, $pars)
    {
        $str = '';
        $rootNode = Catalog::find()->where(['nickname' => $nn, 'active' => 1])->one();
        if(!$rootNode){
            return $str;
        }
        $query = Catalog::find()
            ->where(['root' => $rootNode->id])
            ->andWhere(['<>', 'nickname', $nn])
            ->addOrderBy('root, lft');

        $selected = [];
        if ($this->_idsIn) {
            $selected = Catalog::find()->where(['root' => $rootNode->id])->andWhere("uid IN ({$this->_idsIn})")->all();
        }

        $value = [];
        foreach ($selected as $index => $item) {
            if(in_array($item->uid, $this->_binds)){
                $value[] = $item->id;
            }
        }

        $str .= $rootNode->name .'<br>';
        $str .= TreeViewInput::widget([
            'query'             => $query,
//            'headingOptions'    => ['label' => ''],
            'name'              => $nn . '[]',
            'value'             => implode(',', $value),
            'asDropdown'        => $pars['asDropdown'] ?? true,
            'multiple'          => $pars['multiple'] ?? false,
            'fontAwesome'       => $pars['fontAwesome'] ?? false,
            'rootOptions'       => [
                'label' => '',
                'class'=>'hide'
            ],
            'treeOptions' => [
                'style' => 'max-height:550px'
            ],
            //'options'         => ['disabled' => true],
        ]);

        return $str;
    }
}