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
use fedornabilkin\binds\models\Catalog;
use fedornabilkin\binds\models\Seo;
use fedornabilkin\binds\models\Uid;
use yii\db\ActiveQuery;
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

    /**
     * Перечислены модели, которые связываются один к одному. При удалении родительской модели
     * такая модель также будет удалена, при наличии связи через поле uid_content
     *
     * Например к моделе Post привязаны модели Seo и Comment. Т.к. Seo и Comment могут принадлежать
     * только к одной модели Post, то эти модели необходимо указать в массиве
     * метода BindModel::getChildModels()
     *
     * Post
     * ...
     * public function getChildModels(){
     *    return array_merge(parent::getChildModels(), [Comment::class]);
     * }
     * ...
     *
     * Во всех подчиненных моделях должны быть поля uid и uid_content
     * при чем поле table.uid должно иметь внешний ключ на uids.id
     *
     * @see delete
     * @return array
     */
    public function getChildModels()
    {
        return [
            Seo::class,
        ];
    }

    /**
     * При удалении модели, необходимо удалять запись из таблицы Uid::tableName()
     * при условии, что создан внешний ключ table.uid -> uids.id
     * В таком случае будет удалена запись из таблицы Uid::tableName(), а также сама модель
     *
     * В других случаях необходимо переопределить метод в дочерней модели и описать свою логику удаления
     *
     * @return false|int
     */
    public function delete()
    {
        $uids = [$this->uid];

        // search uids hasOne models
        $removeModels = $this->getChildModels();

        foreach ($removeModels as $class){
            $model = \Yii::createObject(['class' => $class]);
            $rows = $model::findAll(['uid_content' => $this->uid]);

            foreach($rows as $row){
                $uids[] = $row->uid;
            }
        }

        // remove all uids and models
        $models = Uid::findAll($uids);
        foreach ($models as $model) {
            $model->delete();
        }
        return true;

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
     * @return ActiveQuery
     */
    public function getUids()
    {
        return $this->hasOne(Uid::className(), ['id' => 'uid']);
    }


    /**
     * @return ActiveQuery
     */
    public function getBinds()
    {
        $query = $this->hasMany(Bind::class, ['uid' => 'uid']);
        return $query;
    }


    /**
     * @return ActiveQuery
     */
    public function getBindsArray()
    {
        $query = $this->hasMany(Bind::class, ['uid' => 'uid']);
        return $query->asArray();
    }


    /**
     * @return ActiveQuery
     */
    public function getCatalog()
    {
        $query = $this->hasMany(Catalog::class, ['uid' => 'uid_bind'])
            ->viaTable(Bind::tableName(), ['uid' => 'uid']);
        return $query;
    }


    /**
     * @return ActiveQuery
     */
    public function getCatalogArray()
    {
        $query = $this->hasMany(Catalog::class, ['uid' => 'uid_bind'])
            ->viaTable(Bind::tableName(), ['uid' => 'uid']);
        return $query->asArray();
    }


    /**
     * Возвращает запрос на получение списка узлов для корневого узла по nickname
     *
     * @param $nn
     * @return BindQuery
     */
    public function getCatalogByNickname($nn)
    {
        $root = Catalog::findOne(['nickname' => $nn]);
        /** @var BindQuery $query */
        $query = $this->hasMany(Catalog::class, ['uid' => 'uid_bind'])
            ->where(['root' => $root->id])
            ->viaTable(Bind::tableName(), ['uid' => 'uid']);
        return $query;
    }


    /**
     * @return ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['uid_content' => 'uid']);
    }

    /**
     * @return integer
     */
    public function getCreateTime()
    {
        return $this->uids->created_at;
    }
    /**
     * @return integer
     */
    public function getUpdateTime()
    {
        return $this->uids->updated_at;
    }

}