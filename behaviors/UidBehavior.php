<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 13:05
 */

namespace fedornabilkin\binds\behaviors;


use fedornabilkin\binds\models\Uid;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class UidBehavior
 * @property ActiveRecord $owner
 * @package fedornabilkin\behaviors
 */
class UidBehavior extends Behavior
{
    /** @var int */
    public $insertStatus = 1;

    /** @return array */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',

        ];
    }

    /**
     *
     */
    public function beforeInsert() {

        $model = $this->_getOwnerModel();

        if (!$model->uid) {
            $uid = new Uid();
            if ($this->insertStatus) {
                $uid->status = $this->insertStatus;
            }

            $uid->table_name = $model::tableName();
            $uid->save();
            $model->uid = Uid::findOne($uid->id)->id;
        }
    }

    /**
     *
     */
    public function beforeUpdate() {

        $model = $this->_getOwnerModel();
        $uid = Uid::find()->where(['id' => $model->uid])->one();

        if ($uid) {
            $uid->save();
        }else{
            $this->beforeInsert();
        }
    }

    /**
     * @return ActiveRecord
     */
    private function _getOwnerModel()
    {
        return $this->owner;
    }

}