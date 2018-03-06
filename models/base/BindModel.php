<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 14:19
 */

namespace fedornabilkin\binds\models\base;

use fedornabilkin\binds\behaviors\UidBehavior;
use fedornabilkin\binds\models\Bind;
use fedornabilkin\binds\models\Uid;
use yii\db\ActiveRecord;

/**
 * Class BindModel
 * @package fedornabilkin\models
 */
class BindModel extends ActiveRecord
{
    /**
     * @return array the behavior configurations.
     */
    public function behaviors()
    {
        return array_merge_recursive(parent::behaviors(), [
            // запускаем первым
            'UidBehavior' => [
                'class' => UidBehavior::class
            ],
        ]);
    }

    public function beforeDelete()
    {
        return parent::beforeDelete();
    }

    /**
     * При удалении модели, необходимо удалять запись из таблицы Uid::tableName()
     * при условии, что создан внешний ключ table.uid -> uids.id
     *
     * В других случаях необходимо переопределить метод в модели и описать свою логику удаления
     *
     * @return false|int
     */
    public function delete()
    {
        $uid = Uid::findOne($this->uid);
        return $uid->delete();
    }

    /**
     * @return BindQuery
     */
    public static function find()
    {
        return new BindQuery(get_called_class());
    }

    /**
     * @param $condition
     * @return static BindModel instance
     */
    public static function findOneFiltered($condition)
    {
        return static::findByCondition($condition)->filterAvailable()->one();
    }

    /**
     * Return query with filterAvailable filter by default.
     *
     * @return BindQuery
     */
    public static function findFiltered()
    {
        $query = new BindQuery(get_called_class());
        $query->filterAvailable();
        return $query;
    }


    /**
     * @return BindQuery
     */
    public function getBinds($table_name = false)
    {
        /** @var BindQuery $query */
        $query = $this->hasMany(Bind::class, ['uid' => 'uid_bind']);

        if ($table_name) {
            $query = $query->andWhere(['table_name' => $table_name]);
        }

        return $query->filterAvailable();
    }


    public function getBindModel($model_name)
    {
        /** @var BindQuery $query */
        $query = $this->hasMany($model_name, ['uid' => 'uid_bind'])
            ->viaTable(Bind::class, ['uid' => 'uid']);
        return $query->filterAvailable();
    }

    public function getUidsCreateTime()
    {
        return $this->uids->create_at;
    }
    public function getUidsUpdateTime()
    {
        return $this->uids->update_at;
    }

}