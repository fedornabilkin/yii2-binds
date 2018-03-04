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

    public function beforeInsert() {

        $model = $this->getOwnerModel();

        if (!$model->uid) {
            $uid = new Uid();
            if ($this->insertStatus) {
                $uid->status = $this->insertStatus;
            }
//            $uid->id_user   = self::getUserId();
//            $uid->id_action = 'create';
            $uid->table_name = $model::tableName();
            $uid->save();
            $model->uid = Uid::findOne($uid->id)->id;
        }
    }

    /**
     * Update action and id_user before model update
     */
    public function beforeUpdate() {

        $model = $this->getOwnerModel();
        $uid = Uid::find()->where(['id' => $model->uid])->one();

        if ($uid) {
            $uid->save();
        }else{
            $this->beforeInsert();
        }
    }

    private function getOwnerModel()
    {
        return $this->owner;
    }

    public function getUids()
    {
        return $this->owner->hasOne(Uid::class, ['id' => 'uid']);
    }

    public function getStatus()
    {
//        return $this->owner->hasOne(PmStatus::class, ['id' => 'id_status'])
//            ->viaTable('pm_uids', ['uid' => 'uid']);
    }


    private static function getUserId(){
        return \Yii::$app->user->identity->id;
    }

}