<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 14:19
 */

namespace fedornabilkin\models\base;

use fedornabilkin\behaviors\UidBehavior;
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
        return array_merge(parent::behaviors(), [
            // запускаем первым
            'UidBehavior' => [
                'class' => UidBehavior::class
            ],
        ]);
    }

    /**
     * @return ContentQuery
     */
    public static function find()
    {
        return new ContentQuery(get_called_class());
    }

    /**
     * @param $condition
     * @return static Content instance
     */
    public static function findOneFiltered($condition)
    {
        return static::findByCondition($condition)->filterAvailable()->one();
    }

    /**
     * Return query with filterAvailable filter by default.
     *
     * @return ContentQuery
     */
    public static function findFiltered()
    {
        $query = new ContentQuery(get_called_class());
        $query->filterAvailable();
        return $query;
    }

    public function getUidsCreateTime(){return $this->uids->create_at;}
    public function getUidsUpdateTime(){return $this->uids->update_at;}
}