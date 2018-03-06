<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 02.03.2018
 * Time: 22:19
 */

namespace fedornabilkin\binds\behaviors;


use fedornabilkin\binds\models\Seo;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\View;

class SeoBehavior extends Behavior
{

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
        $model = $this->owner;

        if (!$model->uid){
            return true;
        }

        if ($model instanceof Seo) {
            return ($model->validate()) ? $model : false;
        }

        // поискать seo через бинды
        // вытащить модель, если есть или создать новую

        $post = \Yii::$app->request->post();

        $seo = Seo::findOneFiltered(['uid_content' => $model->uid]) ?: new Seo();
        $seo->load($post);
        $seo->uid_content = $model->uid;

        $alias = $seo->alias ?: ($model->title ?? ($seo->title ?: '') );
        $seo->alias = (new Seo)->prepareAlias($alias);

        if (!$seo->validate()) {
            $model->addError('seo', 'error');
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
        /** @var View $view */
        $view = $this->owner;

        $seo = Seo::loadMeta();

        if (!$seo) {
            return false;
        }

        $view->title = $seo->title;
        $view->registerMetaTag(['name' => 'title', 'content' => $seo->title], 'title');
        $view->registerMetaTag(['name' => 'keywords', 'content' => $seo->keywords], 'keywords');
        $view->registerMetaTag(['name' => 'description', 'content' => $seo->description], 'description');
    }
}