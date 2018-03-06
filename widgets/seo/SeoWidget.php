<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 04.03.2018
 * Time: 0:33
 */

namespace fedornabilkin\binds\widgets\seo;


use fedornabilkin\binds\models\base\BindModel;
use fedornabilkin\binds\models\Seo;
use yii\base\Widget;

class SeoWidget extends Widget
{
    /** @var  BindModel */
    public $model;
    /** @var  Seo */
    protected $seoModel;
    protected $params;


    public function init()
    {
        parent::init();
        $this->prepareModel();
    }

    public function run()
    {
        echo $this->render('index', ['model' => $this->seoModel]);
    }

    public function setAlias(){
        // если нет заголовка и нет заголовка СЕО или он пустой
        if (empty($this->model->title) && $this->seoModel->title == '' ){
            return '';
        }else{
            $title = $this->model->title ?? $this->seoModel->title;
        }

        return $this->seoModel->prepareAlias($title);
    }

    protected function prepareModel()
    {
        $this->seoModel = $this->model->getBindModel(Seo::class)->all();
        $this->seoModel = $this->seoModel[0] ?? new Seo();
//        var_dump($this->seoModel);exit;
//        $this->seoModel = Seo::findOneFiltered(['uid_content' => $this->model->uid]) ?: new Seo();
        $this->seoModel->load(\Yii::$app->request->post());
        if ($this->seoModel->isNewRecord) {
            $this->seoModel->title = $this->model->title ?? ($this->seoModel->title ?: '');
            $this->seoModel->alias = $this->seoModel->alias ?? $this->setAlias();
            $this->seoModel->description = isset($this->model->preview) ? strip_tags($this->model->preview) : '';
        }
    }
}