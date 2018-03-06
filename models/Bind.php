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
    public static function addBinds($uid, $binds)
    {
        $arr = [];
        foreach ($binds as $uidBind) {
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
        if (!$binds) {
            return false;
        }
        self::deleteAll([
            'uid' => $uid,
            'uid_bind' => $binds
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
        $nowBinds = self::find()->where(['uid' => $uid])->asArray()->all();
        $nowUidsBinds = array_column($nowBinds, 'uid_bind');

        $removeUidsBinds = array_diff( $nowUidsBinds, $uidsBinds);
        $addUidsBinds = array_diff( $uidsBinds, $nowUidsBinds);

        // удаляем отвязанные бинды
        self::removeBinds($uid, $removeUidsBinds);

        // добавляем новые бинды
        self::addBinds($uid, $addUidsBinds);

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
}