<?php
/**
 * Created by PhpStorm.
 * User: TOSHIBA-PC
 * Date: 02.03.2018
 * Time: 22:22
 */

namespace fedornabilkin\models;


use fedornabilkin\models\base\BindModel;
use yii\helpers\StringHelper;

class Seo extends BindModel
{


    public static function tableName()
    {
        return '{{%bind_seo}}';
    }

    public function rules()
    {
        return [
            [['title', 'keywords', 'description', 'path_content', 'alias', 'h1'], 'string'],
            [['uid_content'], 'integer'],
            [['uid_content', 'alias'], 'required'],
            [['alias'], 'unique'],
            [['description','title', 'path_content', 'keywords', 'h1'], 'string','max' => 150]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Заголовок',
            'keywords' => 'Ключевые слова',
            'description' => 'Описание',
            'path_content' => 'Путь к разделу',
            'alias' => 'ЧПУ',
            'h1' => 'H1'
        ];
    }

    protected static function loadContentMeta($uid)
    {
        if (!$model = self::findOneFiltered(['uid_content' => $uid])) {
            //$this->addError('uid_content', 'error');
            return false;
        };
        return $model;
    }

    public static function loadMeta()
    {
        $get = \Yii::$app->request->get();
        $path = \Yii::$app->request->getPathInfo();
        $path = ($path != '') ? $path : ':mainpage:';
        $alias = (isset($get['alias'])) ? $get['alias'] : null;

        $uid = (Page::findFiltered()->where(['slug_compiled' => $path])->orWhere(['route' => $path])->one()->uid) ?? self::findFiltered()->where(['alias' => $alias])->one() ?? false;
        return (is_int($uid)) ? self::loadContentMeta($uid) : $uid;
    }

    public function getBinds()
    {
        return $this->hasMany(Bind::class, ['uid' => 'uid']);
    }

    public static function getSeoByUid($uid){
        $model = Seo::find()->where(['uid_content' => $uid])->asArray()->one();
        return $model;
    }

    public function prepareAlias($str){
        if($str == ''){
            return $str;
        }

        $replaceList = $this->getReplaceList();

        $alias = mb_strtolower($str);
        $source = str_replace(array_keys($replaceList), array_values($replaceList), $alias);
        $source = preg_replace('/[^a-zA-Z0-9\-]/', '', $source);
        return StringHelper::truncate($source, 150, '');
    }

    public function getReplaceList($symbol = '')
    {
        $arr = [

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g',
            'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'щ' => 'shch', 'ш' => 'sh', 'ь' => '',
            'ы' => 'y', 'ъ' => '', 'э'  => 'je', 'ю' => 'yu',
            'я' => 'ya',

            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'JO', 'Ж' => 'ZH', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L',
            'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P',
            'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH',
            'Ш' => 'SH', 'Ъ' => '', 'Щ' => 'SHCH', 'Ы' => 'Y',
            'Ь' => '', 'Э' => 'JE', 'Ю' => 'YU', 'Я' => 'YA',
            ' ' => '-',

//            'э' => 'ea',
//            'э' => 'e',
//            'й' => 'y',
//            'Э' => 'E',
//            'Й' => 'Y',
//            'Э' => 'E',
        ];

        return $symbol ? $arr[$symbol] : $arr;
    }

}