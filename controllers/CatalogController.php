<?php

namespace fedornabilkin\binds\controllers;

use fedornabilkin\binds\models\Catalog;

/**
 * Class CatalogController
 * @package fedornabilkin\binds\controllers
 */
class CatalogController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = Catalog::find()->addOrderBy('root, lft');
        return $this->render('index', [
            'query' => $query,
        ]);
    }

}
