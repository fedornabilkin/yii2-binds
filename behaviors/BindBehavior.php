<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:58
 */

namespace fedornabilkin\binds\behaviors;

use fedornabilkin\binds\models\Bind;
use fedornabilkin\binds\models\Catalog;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class BindBehavior
 * @package fedornabilkin\behaviors
 */
class BindBehavior extends Behavior
{

    public $tree = [
        'nicknames' => [],
    ];

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate'
        ];
    }

    public function beforeUpdate() {
        $this->saveBinds();
    }

    /**
     * Обработка сущностей Catalog, которые должны быть привязаны к модели
     */
    public function saveBinds()
    {
        if ($this->tree['nicknames']) {

            $ids = [];
            // Собираем все id из запроса
            foreach ($this->tree['nicknames'] as $nn => $pars) {
                $ids = array_merge($ids, $this->_getBindId($nn));
            }

            // по id получаем список uid, которые необходимо привязать
            if ($ids) {
                $ids_str = implode(',', $ids);
                $models = Catalog::find()->where("id IN ($ids_str)")->all();
            } else {
                $models = [];
            }

            $binds = [];
            foreach ($models as $model) {
                $binds[] = $model->uid;
            }

            $binds = array_filter($binds);
            Bind::setBinds($this->owner->uid, $binds);
        }
    }

    /**
     * @param $nn string
     * @return array
     */
    private function _getBindId($nn)
    {
        $r = \Yii::$app->request;
        $values = $r->post($nn);

        $ids = [];
        if (!is_array($values)) {
            return $ids;
        }
        foreach ($values as $val) {
            if($val){
                $ids[] = $val;
            }
        }
        return $ids;
    }
}