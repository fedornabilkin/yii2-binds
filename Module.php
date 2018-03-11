<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 09.03.2018
 * Time: 11:09
 */


namespace fedornabilkin\binds;

/**
 * Class Module
 * @package fedornabilkin\binds
 */
class Module extends \yii\base\Module {

    public $controllerNamespace = 'fedornabilkin\binds\controllers';

    public function init() {
        parent::init();
    }

}