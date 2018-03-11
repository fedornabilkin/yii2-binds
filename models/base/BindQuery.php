<?php
/**
 * Created by PhpStorm.
 * User: smirnovrm
 * Date: 01.03.2018
 * Time: 14:51
 */

namespace fedornabilkin\binds\models\base;


use fedornabilkin\binds\models\Uid;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class BindQuery
 * @package fedornabilkin\binds\models\base
 */
class BindQuery extends ActiveQuery
{
    /**
     * Filter query by different conditions depends on app and user role
     * @return $this
     */
    public function filterAvailable()
    {
        return $this->status(Uid::STATUS_PUBLISHED);
    }

    /**
     * Filter query by status condition
     *
     * @param int $status type of status. Use constants from Uid
     * @param bool $equal which condition to use (equal or not equal)
     * @return BindQuery
     */
    public function status(int $status, bool $equal = true): self
    {
        $sign = $equal ? '=' : '<>';
        $uniq = uniqid();
        $modelTable = $this->modelClass::tableName();
        $uidsTable = Uid::tableName();

        $subquery = "(SELECT $modelTable.* FROM $modelTable INNER JOIN $uidsTable ua__$uniq ON ua__$uniq.id = $modelTable.uid AND ua__$uniq.status $sign $status)";
        return $this->from([$modelTable => $subquery]);
    }

    /**
     * @param $binds integer|string|array Группа условий
     *  Первый уровень вложенности формирует AND condition
     *  Второй - OR condition
     *      Пример:
     *      [1, 2, 3] => (1 AND 2 AND 3)
     *      [1, [2, 3]] => (1 AND (2 OR 3))
     *      [[1, 2, 3]] => (1 OR 2 OR 3)
     * @return $this|BindQuery
     */
//    public function filterBinded($binds)
//    {
//        // Формируем первый уровень вложенности если передано простое значение
//        $binds = is_array($binds) ? $binds : [$binds];
//
//        $result = $this;
//        foreach ($binds as $bind) {
//            if (empty($bind)) continue;
//
//            if (is_array($bind)) {
//                // есть второй уровень вложенности, условия из него применятся как AND (cond1 OR cond2 ...)
//                $result = (array_sum($bind) > 0)
//                    ? $result->filterBindedByUid($bind)
//                    : $result->filterBindedCatalogByNickname($bind);
//            } else {
//                // bind содержит одно условие, оно будет применено как AND
//                $result = ((int)$bind > 0)
//                    ? $result->filterBindedByUid($bind)
//                    : $result->filterBindedCatalogByNickname($bind);
//            }
//        }
//
//        return $result;
//    }

    /**
     * @param $uid
     * @return $this
     */
//    public function filterBindedByUid($uid)
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        if (!is_array($uid)) {
//            return $this->innerJoin("pm_binds bc{$uid}", "bc{$uid}.uid = {$tableName}.uid AND bc{$uid}.uid_bind = $uid");
//        } else {
//            $uniq = uniqid();
//            return $this->innerJoin("pm_binds bc{$uniq}", "bc{$uniq}.uid = {$tableName}.uid AND bc{$uniq}.uid_bind IN(" . implode(',', $uid) . ")");
//        }
//
//    }

    /**
     * @param $nn
     * @return $this
     */
//    public function filterBindedCatalogByNickname($nn)
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        $uniq = uniqid();
//        if (!is_array($nn)) {
//            return $this->innerJoin("pm_binds bc{$nn}{$uniq}", "bc{$nn}{$uniq}.uid = {$tableName}.uid AND bc{$nn}{$uniq}.uid_bind IN (SELECT uid FROM pm_catalog WHERE nickname = '{$nn}')");
//        } else {
//
//            return $this->innerJoin("pm_binds bc{$uniq}", "bc{$uniq}.uid = {$tableName}.uid AND bc{$uniq}.uid_bind IN (SELECT uid FROM pm_catalog WHERE nickname IN('" . implode("', '", $nn) . "'))");
//        }
//    }
//
//    public function filterBindedCatalogByNicknameExceptNN($nn, $notnn = [])
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        $uniq = uniqid();
//        $model = $this;
//
//        if (!is_array($nn)) {
//            $model = $model->innerJoin("pm_binds bc{$nn}{$uniq}", "bc{$nn}{$uniq}.uid = {$tableName}.uid AND bc{$nn}{$uniq}.uid_bind IN (SELECT uid FROM pm_catalog WHERE nickname = '{$nn}')");
//        } else {
//
//            $model = $model->innerJoin("pm_binds bc{$uniq}", "bc{$uniq}.uid = {$tableName}.uid AND bc{$uniq}.uid_bind IN (SELECT uid FROM pm_catalog WHERE nickname IN('" . implode("', '", $nn) . "'))");
//        }
//
//        // not nickname
//        if (is_array($notnn) && count($notnn)) {
//            $model = $model->where("not exists (SELECT * FROM pm_binds b WHERE b.uid = {$tableName}.uid AND b.uid_bind in (SELECT uid FROM pm_catalog WHERE nickname IN('" . implode("', '", $notnn) . "')))");
//        }
//
//        return $model;
//    }

    /**
     * TODO another place - not this class
     *
     * @param $uids
     * @return $this|BindQuery
     */
//    public function findOrderByUids($uids, $isGroupBy = false)
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        $model = $this;
//        if ($uids) {
//            $model = $model
//                ->where("{$tableName}.uid IN (" . implode(',', $uids) . ')');
//            if ($isGroupBy) {
//                $model = $model->groupBy("{$tableName}.uid");
//            }
//            $model = $model->orderBy([new Expression(sprintf("find_in_set({$tableName}.uid::text, '%s')", implode(",", $uids)))]);
//        } else {
//            return false;
//        }
//
//        return $model;
//
//    }

    /**
     * TODO $nn is not only array
     *
     * @param $nn
     * @return $this|BindQuery
     */
//    public function filterNotBindedCatalogByNickname($nn)
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        $model = $this;
//        if ($nn && is_array($nn)) {
//            $model = $model->where("not exists (SELECT * FROM pm_binds b WHERE b.uid = {$tableName}.uid AND b.uid_bind in (SELECT uid FROM pm_catalog WHERE nickname IN('" . implode("', '", $nn) . "')))");
//        }
//        return $model;
//    }

    /**
     * TODO $binds is not only array
     *
     * @param $binds
     * @return $this|BindQuery
     */
//    public function filterNotBinded($binds)
//    {
//        $modelClass = $this->modelClass;
//        $tableName = $modelClass::tableName();
//        $model = $this;
//        $uniq = uniqid();
//        if ($binds && is_array($binds)) {
//            $model = $model->where("not exists (SELECT * FROM pm_binds b_{$uniq} WHERE b_{$uniq}.uid = {$tableName}.uid AND b_{$uniq}.uid_bind in (" . implode(',', $binds) . "))");
//        }
//        return $model;
//    }
}