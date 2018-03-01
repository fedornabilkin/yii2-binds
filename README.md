Связывание сущностей
====================
Связывает модели и сущности посредством срединной таблицы

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist fedornabilkin/yii2-binds "*"
```

or add

```
"fedornabilkin/yii2-binds": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \fedornabilkin\binds\AutoloadExample::widget(); ?>```