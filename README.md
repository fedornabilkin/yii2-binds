Связывание сущностей
====================
Связывает модели и сущности посредством промежуточной таблицы на основании
глобальнго uid. 

Расширение требует php 7.0 и выше. Использует модуль [yii2-tree-manager](https://github.com/kartik-v/yii2-tree-manager)
и [SAR (simple ajax requests)](https://github.com/fedornabilkin/sar)

Установка
-----

Установите расширение с помощью [composer](http://getcomposer.org/download/).

```
php composer.phar require --prefer-dist fedornabilkin/yii2-binds "*"
```

или добавьте в секцию require в файл `composer.json`.

```
"fedornabilkin/yii2-binds": "dev-master"
```

Выполните миграции
-----

``` php yii migrate --migrationPath=@fedornabilkin/binds/migrations```

Управление каталогом `/binds/catalog`

Пример использования
-----

Модель

```php
use fedornabilkin\binds\behaviors\BindBehavior;
use fedornabilkin\binds\behaviors\SeoBehavior;
use fedornabilkin\binds\models\base\BindModel;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $uid
 * @property string $title
 * @property string $post
 */
class Post extends BindModel
{

    public function behaviors()
    {
        return array_merge_recursive(parent::behaviors(), [
            'SeoBehavior' => [
                'class' => SeoBehavior::class,
            ],
            'BindsBehavior' => [
                'class' => BindBehavior::class,
                'tree' => [
                    // никнэймы корневых узлов дерева каталога
                    'nicknames' => [
                        'visible' => [
                            'multiple' => false, // единичный или множественный выбор
                        ],
                        'categories' => [
                            'multiple' => false,
                        ],
                        'tags' => [
                            'multiple' => true,
                            'asDropdown' => false, // развернутое состояние
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post'], 'string'],
            [['title'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'post' => 'Post',
        ];
    }

    /**
     * модели hasOne, указать для удаления дочерней модели
     * если она связана с родительской один-к-одному
     */
    public function getChildModels()
    {
        return array_merge(parent::getChildModels(), [
            'comment' => Comment::class,
        ]);
    }
}
```

Форма заполнения данными

```php
<?php
use fedornabilkin\binds\models\Catalog;
use fedornabilkin\binds\models\Uid;
use yii\helpers\Html;
use kartik\tree\TreeViewInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */

$this->title = 'Update Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
```

```html
<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-8">

            <?= \fedornabilkin\binds\widgets\status\StatusWidget::widget(['model' => $model])?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'post')->textarea(['rows' => 6]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?= \fedornabilkin\binds\widgets\seo\SeoWidget::widget(['model' => $model])?>
        </div>

        <div class="col-xs-12 col-sm-4">
            <?= \fedornabilkin\binds\widgets\binds\BindsWidget::widget(['model' => $model])?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
```

Чтобы SEO-данные отобразились на странице в metatags, необходимо добавить
`SeoBehavior` в компонент `View` в конфиграционном файле. Также в адресе
необходимо передать `?alias=adres-stranicy`.

```php
'components' => [
    ...
    
    'view' => [
        'as seo' => [
            'class' => \fedornabilkin\binds\behaviors\SeoBehavior::class,
        ],
    ],
],

'modules' => [

    ...

    'binds' => [
        'class' => 'fedornabilkin\binds\Module',
    ],
    'treemanager' => [
        'class' => 'kartik\tree\Module',
        'dataStructure' => [
            'keyAttribute' => 'id',
        ],
    ],
],
```

[Инструкция и обсуждение](http://www.masterwebs.ru/topic/29375-lp-rasshirenie-yii2-binds/)