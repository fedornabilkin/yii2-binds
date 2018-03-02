Связывание сущностей
====================
Связывает модели и сущности посредством срединной таблицы.

Расширение требует php 7.0 и выше

Установка
------------

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

```php
yii migrate --migrationPath=@fedornabilkin/bind/migrations```

