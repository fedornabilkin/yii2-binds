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
use function Symfony\Component\Debug\Tests\testHeader;
use yii\base\Widget;

/**
 * Class SeoWidget
 * @package fedornabilkin\binds\widgets\seo
 */
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
        $this->params['post'] = \Yii::$app->request->post();
        $this->prepareModel();
    }

    public function run()
    {
        echo $this->render('index', ['model' => $this->seoModel]);
    }

    /**
     * Если нет привязанной модели Seo, то создаем новую
     * и загружаем данными из post-запроса на случай ошибки, чтобы формы не затерлись
     */
    protected function prepareModel()
    {
        $this->seoModel = Seo::findOne(['uid_content' => $this->model->uid]) ?? new Seo();
        $this->seoModel->load($this->params['post']);

        if ($this->seoModel->isNewRecord) {
            $this->seoModel->title = $this->model->title ?? ($this->seoModel->title ?: '');
            $this->seoModel->alias = $this->seoModel->alias ?? $this->setAlias();
        }
    }

    /**
     * @return string
     */
    public function setAlias(){
        // если нет заголовка и нет заголовка СЕО или он пустой
        if (empty($this->model->title) && $this->seoModel->title == '' ){
            return '';
        }else{
            $title = $this->model->title ?? $this->seoModel->title;
        }

        return $this->seoModel->prepareAlias($title);
    }
}