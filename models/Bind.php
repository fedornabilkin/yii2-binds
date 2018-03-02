<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 12:44
 */

namespace fedornabilkin\models;

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
        if ($removeUidsBinds) {
            self::deleteAll([
                'uid' => $uid,
                'uid_bind' => $removeUidsBinds
            ]);
        }

        // добавляем новые бинды
        if ($addUidsBinds)
        {
            $arr = [];
            foreach ($addUidsBinds as $uidBind)
            {
                $arr[] = [$uid, $uidBind];
            }
            $conn = \Yii::$app->db;
            $conn->createCommand()->batchInsert(Bind::tableName(), [
                'uid',
                'uid_bind'
            ], $arr )->execute();
        }
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