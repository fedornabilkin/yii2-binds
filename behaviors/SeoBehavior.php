<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 02.03.2018
 * Time: 22:19
 */

namespace fedornabilkin\binds\behaviors;


use fedornabilkin\binds\models\base\BindModel;
use fedornabilkin\binds\models\Bind;
use fedornabilkin\binds\models\Seo;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\View;

class SeoBehavior extends Behavior
{
    /** @var BindModel */
    private $_ownerModel;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            View::EVENT_BEGIN_PAGE => 'beginPage',
        ];
    }

    protected function updateSeo()
    {
        if ($seo = $this->validateSeo()) {
            $seo->save(false);

            Bind::addBinds($this->_ownerModel->uid, [$seo->uid]);
        }else{
            return false;
        }
    }

    public function beforeValidate()
    {
        return $this->validateSeo() ? true : false;
    }

    public function beforeInsert()
    {
        $this->updateSeo();
    }

    public function beforeUpdate()
    {
        $this->updateSeo();
    }

    public function validateSeo(){

        $this->_ownerModel = $this->owner;

        if (!$this->_ownerModel->uid){
            return true;
        }

        if ($this->_ownerModel instanceof Seo) {
            return ($this->_ownerModel->validate()) ? $this->_ownerModel : false;
        }

        $post = \Yii::$app->request->post();

        $seo = $this->_ownerModel->getBindModel(Seo::class)->all()[0] ?? new Seo();
        $seo->load($post);

        $alias = $seo->alias ?: ($this->_ownerModel->title ?? ($seo->title ?: '') );
        $seo->alias = (new Seo)->prepareAlias($alias);

        if (!$seo->validate()) {
            $this->_ownerModel->addError('seo', 'error');
            $msg = '';
            foreach ($seo->errors as $error) {
                $msg .= ' '.$error[0];
            }
            \Yii::$app->session->setFlash('error', $msg);
            return false;
        }
        return $seo;
    }

    public function beginPage()
    {
        if (!$seo = Seo::loadMeta()) {
            return false;
        }

        /** @var View $view */
        $view = $this->owner;

        $view->title = $seo->title;
        $view->registerMetaTag(['name' => 'title', 'content' => $seo->title], 'title');
        $view->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords], 'keywords');
        $view->registerMetaTag(['name' => 'description', 'content' => $seo->description], 'description');
    }
}