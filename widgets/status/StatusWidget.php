<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 11.03.2018
 * Time: 15:41
 */

namespace fedornabilkin\binds\widgets\status;

use fedornabilkin\binds\models\base\BindModel;
use fedornabilkin\binds\models\Uid;
use fedornabilkin\binds\widgets\status\assets\StatusAsset;
use yii\base\Widget;

/**
 * Class StatusWidget
 * @package fedornabilkin\binds\widgets\status
 */
class StatusWidget extends Widget
{
    /** @var BindModel */
    public $model;

    public function init() {
        parent::init();
        $this->registerAssets();
    }

    public function run()
    {
        if (!$this->model or $this->model->isNewRecord) {
            echo '';
            return;
        }

        echo $this->render('index', [
            'model' => $this->model,
            'btns' => Uid::getStatuses(),
        ]);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {
        $view = $this->getView();
        StatusAsset::register($view);
    }
}