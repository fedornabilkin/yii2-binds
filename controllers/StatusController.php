<?php

namespace fedornabilkin\binds\controllers;

use fedornabilkin\binds\models\Uid;
use yii\filters\VerbFilter;

/**
 * Class StatusController
 * @package fedornabilkin\binds\controllers
 */
class StatusController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'save' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actionSave()
    {

        $post = \Yii::$app->request->post();
        \Yii::$app->response->format = \Yii\web\Response::FORMAT_JSON;

        $result = ['errors' => ['Not valid status']];
        $statuses = Uid::getStatuses();

        if(!in_array($post['status'], array_keys($statuses))){
            return $result;
        }

        $uid = Uid::find()
            ->where('id = :id', [':id' => $post['uid']])
            ->one();

        if($uid->status = $post['status']){
            $uid->save();
            $result = ['text' => 'Ok', 'success' => true];
        }

        return $result;
    }

}
