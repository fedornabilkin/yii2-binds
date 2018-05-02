<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 02.03.2018
 * Time: 22:19
 */

namespace fedornabilkin\binds\behaviors;


use fedornabilkin\binds\models\base\BindModel;
use fedornabilkin\binds\models\Seo;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\View;

/**
 * Class SeoBehavior
 * @package fedornabilkin\binds\behaviors
 */
class SeoBehavior extends Behavior
{
    /** @var  Seo */
    public $seoModel;
    /** @var BindModel */
    private $_ownerModel;

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',

            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',

            View::EVENT_BEGIN_PAGE => 'beginPage',
        ];
    }

    /**
     * Сохраняет модель Seo после успешной валидации
     * @return bool
     */
    protected function updateSeo()
    {
        if ($this->validateSeo()) {
            $this->seoModel->save(false);
            return true;
//            $this->_ownerModel->link('seo', $this->seoModel);
        }else{
            return false;
        }
    }

    public function beforeValidate()
    {
        return $this->validateSeo() ? true : false;
    }

    public function afterInsert()
    {
        $this->updateSeo();
    }

    public function afterUpdate()
    {
        $this->updateSeo();
    }

    public function validateSeo(){

        $this->_ownerModel = $this->owner;

        // Невозможно сохранить Seo, если в родительской модели нет uid
        if (!$this->_ownerModel->uid){
            return true;
        }

        if ($this->_ownerModel instanceof Seo) {
            return ($this->_ownerModel->validate()) ? $this->_ownerModel : false;
        }

        // Если Seo еще не прикреплено к модели (при создании новой записи) , то создаем новую модель Seo
        $this->seoModel = $this->_ownerModel->seo ?? new Seo();
        $this->seoModel->load(\Yii::$app->request->post());

        // Подготовка alias, добавление content_uid (привязать методом link не получается)
        $alias = $this->seoModel->alias ?: ($this->_ownerModel->title ?? ($this->seoModel->title ?: '') );
        $this->seoModel->alias = $this->seoModel->prepareAlias($alias);
        $this->seoModel->uid_content = $this->_ownerModel->uid;

        if (!$this->seoModel->validate()) {
            return $this->_setErrors();
        }
        return true;
    }

    /**
     * Регистрация meta перед отрисовкой страницы
     */
    public function beginPage()
    {
        if (!$seo = Seo::loadMeta()) {
            return;
        }

        /** @var View $view */
        $view = $this->owner;

        $view->title = $seo->title ?? $view->title;
        $view->registerMetaTag(['name' => 'title', 'content' => $view->title], 'title');
        $view->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords], 'keywords');
        $view->registerMetaTag(['name' => 'description', 'content' => $seo->description], 'description');
    }

    /**
     * @return bool
     */
    private function _setErrors(): bool
    {
        $this->_ownerModel->addError('seo', 'error');
        $msg = '';
        foreach ($this->seoModel->errors as $error) {
            $msg .= ' ' . $error[0];
        }
        \Yii::$app->session->setFlash('error', $msg);
        return false;
    }
}