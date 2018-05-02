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
use yii\web\NotFoundHttpException;

/**
 * Class BindModel
 * @package fedornabilkin\models
 */
class BindModel extends ActiveRecord
{

    private $_uids = [];
    private $_nameModels = [];

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
     *    return array_merge(parent::getChildModels(), [Comment::tableName() => Comment::class]);
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
            Seo::tableName() => Seo::class,
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
        $this->_uids = [$this->uid];

        // search uids hasOne models recursive
        $removeModels = $this->getChildModels();
        $this->_getUids($removeModels, $this->uid);

        // remove all uids and models
        $models = Uid::findAll($this->_uids);
        foreach ($models as $model) {
            $model->delete();
        }
        return true;

    }

    /**
     * Собирает uids связанных моделей (Customer->Vacancy->Candidate)
     * У модели Customer в подчиненных является модель Vacancy и она далжна быть указана в массиве,
     * который возвращает Customer::getChildModels(). У модели Vacancy, соответственно Candidate.
     * Таким образом, если мы удалим модель Customer, то будут удалены все модели Vacancy, которые привязаны
     * к Customer, а также для каждой модели Vacancy будут удалены все модели Candidate.
     *
     * @param $models
     * @param $uid
     * @return array
     */
    private function _getUids($models, $uid)
    {
        foreach ($models as $name => $class){
            /** @var $model BindModel */
            $model = \Yii::createObject(['class' => $class]);
            $rows = $model::findAll(['uid_content' => $uid]);

            foreach($rows as $row){
                $this->_uids[] = $row->uid;
            }


            if($child = $model->getChildModels()){
                $this->_uids = array_merge($this->_uids, $this->_getUids($child, $model->uid));
            }
        }

        return $this->_uids;
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
        return $this->hasOne(Uid::class, ['id' => 'uid']);
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
        return $this->getBinds()->asArray();
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
        return $this->getCatalog()->asArray();
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
     * Возвращает модель по ЧПУ
     *
     * @param $alias
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function getModelByAlias($alias)
    {
        $model = self::find()->filterAvailable()
            ->joinWith('seo')
            ->where(['alias' => $alias])
            ->one();

        if(!$model){
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
        return $model;
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