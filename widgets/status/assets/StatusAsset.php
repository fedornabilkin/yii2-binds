<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 11.03.2018
 * Time: 18:40
 */

namespace fedornabilkin\binds\widgets\status\assets;


use yii\web\AssetBundle;

/**
 * Class StatusAsset
 * @package fedornabilkin\binds\widgets\status\assets
 */
class StatusAsset extends AssetBundle
{
    public function init()
    {
        $this->sourcePath = __DIR__;
        parent::init();
    }

    public $js = [
        'js/status.js'
    ];


    public $depends = [
        'yii\web\YiiAsset',
        'fedornabilkin\binds\assets\SarAsset',
    ];

    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV ? true : false, // всегда публиковать актуальные версии
    ];
}