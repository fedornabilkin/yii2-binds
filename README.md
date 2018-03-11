Связывание сущностей
====================
Связывает модели и сущности посредством промежуточной таблицы на основании
глобальнго uid. 

Расширение требует php 7.0 и выше

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

Пример использования
-----

Модель

```php
use fedornabilkin\binds\behaviors\SeoBehavior;
use fedornabilkin\binds\models\base\BindModel;

class Post extends BindModel
{
    public function behaviors()
    {
        return array_merge_recursive(parent::behaviors(), [
            'SeoBehavior' => [
                'class' => SeoBehavior::class,
            ]
        ]);
    }
    
    ...
}
```

Форма заполнения данными

```html
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'post')->textarea(['rows' => 6]) ?>

<?= \fedornabilkin\binds\widgets\seo\SeoWidget::widget(['model' => $model])?>

<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
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
```

Логика расширения подразумевает использование уникальных uid для всех
сущностей проекта, которые необходимо связать между собой посредством
промежуточной таблицы.

Каждая сущность (модель), которая участвует в связывании должна иметь
поле uid, которое заполняется уникальным значением при создании модели,
а также при обновлении, если это поле еще не заполнено. 