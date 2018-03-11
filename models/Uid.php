<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:30
 */

namespace fedornabilkin\binds\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Uid
 * @package fedornabilkin\models
 */
class Uid extends ActiveRecord
{
    CONST STATUS_PUBLISHED = 1;
    CONST STATUS_DRAFT = 2;
    CONST STATUS_DELETED = 3;

    public function behaviors()
    {
        return array_merge_recursive(parent::behaviors(), [
            'TimestampBehavior' => [
                'class' => TimestampBehavior::class,
            ],

        ]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function tableName()
    {
        return 'bind_uids';
    }

    /**
     * Список статусов или название статуса по id
     *
     * @param int $id
     * @return array|string
     */
    public static function getStatuses($id = 0)
    {
        $arr = [
            self::STATUS_PUBLISHED => 'Опубликован',
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_DELETED => 'Удален',
        ];

        return $id ? $arr[$id] : $arr;
    }

}