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
    private static $_nowBinds = [];

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public static function tableName()
    {
        return '{{%bind_binds}}';
    }

    /**
     * Добавляет связи
     *
     * @param $uid
     * @param $binds
     * @return boolean
     */
    public static function addBinds($uid, array $binds)
    {

        $nowUidsBinds = self::_getNowBinds($uid);
        $addUidsBinds = array_diff( $binds, $nowUidsBinds);

        $arr = [];
        foreach ($addUidsBinds as $uidBind) {
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
     * Удаляет связи
     *
     * @param $uid
     * @param $UidsBinds
     * @return boolean
     */
    public static function removeBinds($uid, $binds)
    {
        if (!$binds or !is_array($binds)) {
            return false;
        }

        $nowUidsBinds = self::_getNowBinds($uid);
        $removeUidsBinds = array_diff( $nowUidsBinds, $binds);

        self::deleteAll([
            'uid' => $uid,
            'uid_bind' => $removeUidsBinds
        ]);
        return true;
    }

    /**
     * Обновляет связи (добавляет новые, удаляет старые)
     *
     * @param $uid
     * @param array $uidsBinds
     * @throws \yii\db\Exception
     */
    public static function setBinds($uid, $uidsBinds=[])
    {
        // удаляем отвязанные бинды
        self::removeBinds($uid, $uidsBinds);
        // добавляем новые бинды
        self::addBinds($uid, $uidsBinds);
    }

    /**
     * @param $uid
     * @return array
     */
    public static function getBinds($uid)
    {
        $binds = self::find()->where([
            'uid' => $uid
        ])->asArray()->all();
        $result = [];
        foreach ($binds as $bind)
        {
            $result[] = $bind['uid_bind'];
        }

        return $result;
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