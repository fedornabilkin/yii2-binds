<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 11.03.2018
 * Time: 18:34
 */

namespace fedornabilkin\binds\assets;


use yii\web\AssetBundle;

/**
 * Class SarAsset
 * @package fedornabilkin\binds\assets
 */
class SarAsset extends AssetBundle
{
    public $sourcePath = '@bower';
    public $js = [
        'simple-ajax-requests/dist/sar.js',
    ];
}