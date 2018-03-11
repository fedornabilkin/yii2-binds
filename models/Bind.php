<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:44
 */

namespace fedornabilkin\binds\models;

use yii\db\ActiveRecord;

/**
 * Class Bind
 * @package fedornabilkin\models
 */
class Bind extends ActiveRecord
{
    /** @var array Cache property */
    private static $_nowBinds = [];

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function tableName()
    {
        return 'bind_binds';
    }

    /**
     * Добавляет связи. Если uid из массива $binds нет в привязанных uid
     *
     * @param $uid
     * @param $binds
     * @return boolean
     */
    public static function addBinds($uid, array $binds)
    {

        $nowBinds = self::_getNowBinds($uid);
        $addBinds = array_diff($binds, $nowBinds);

        $arr = [];
        foreach ($addBinds as $uidBind) {
            $arr[] = [$uid, $uidBind];
        }

        if ($arr) {
            $conn = \Yii::$app->db;

            $conn->createCommand()
                ->batchInsert(Bind::tableName(), ['uid', 'uid_bind'], $arr)
                ->execute();
            return true;
        }

        return false;
    }

    /**
     * Удаляет связи. Если некоторых привязанных uid нет в новом массиве $binds
     *
     * @param $uid
     * @param $binds
     * @return boolean
     */
    public static function removeBinds($uid, $binds)
    {
        $nowBinds = self::_getNowBinds($uid);
        $removeBinds = array_diff($nowBinds, $binds);

        self::deleteAll(['uid' => $uid, 'uid_bind' => $removeBinds]);
        return true;
    }

    /**
     * Обновляет связи (добавляет новые, удаляет старые)
     *
     * @param $uid
     * @param array $uidsBinds
     * @return boolean
     */
    public static function setBinds($uid, $uidsBinds=[])
    {
        return self::removeBinds($uid, $uidsBinds) && self::addBinds($uid, $uidsBinds);
    }

    /**
     * @param $uid
     * @return array
     */
    public static function getBinds($uid)
    {
        return self::_getNowBinds($uid);
    }

    /**
     * @param $uid
     * @return array
     */
    private static function _getNowBinds($uid): array
    {
        if (!self::$_nowBinds) {
            self::$_nowBinds = self::find()->where(['uid' => $uid])->asArray()->all();
        }
        return  array_column(self::$_nowBinds, 'uid_bind');
    }
}