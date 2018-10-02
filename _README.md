MLM Matrix
==========

Библиотека для работы с MLM матрицами.


Что такое MLM матрицы ?
-----------------------

В МЛМ в Интернете наиболее популярной формой маркетинг плана является матричный.
Матрицы могут быть разных видов и с разным кол-во уровней. Обычно насчитывается 3 — 4 уровня.
Например кубическая матрица из 3 уровней будет выглядить так:

![demo](./doc/images/view.png "")

После закрытия матрицы, человек на 1 уровне получает вознаграждение, а МЛМ матрица делится еще на несколько матриц
(зависит от типа, например кубическая матрица разделится еще на 3 новые матрицы). После чего новые матрицы ожидают заполнения
и цикл повторяется.


Установка
---------

Предпочтительный способ установки этого виджета через [composer](http://getcomposer.org/download/).

Запустите в консоле

```
php composer.phar require --prefer-dist nepster-web/php-mlm-matrix "*"
```

или добавьте

```
"nepster-web/php-mlm-matrix": "*"
```

в файл `composer.json` в секцию require.


Структура:
---------

**Matrix.php** - Библиотека для работы с матрицами.

**Render.php** - Генератор html кода матрицы.

**shema/matrix.sql** - SQL (MySql) Схема таблиц для матриц.



Примеры использования:
----------------------

**Генерация пустой матрицы**

```php
$view = 2;
$level = 3;
$matrix = new Matrix($view, $level);
$matrix->generation();
$matrix->getArray(); // На выходе массив матрицы
```


**Заполняем матрицу пользователями**

```php
$users = [
    [
        'level' => 0,
        'number' => 0,
        'user' => 'Nepster',
    ]
];
$matrix = new Matrix($view, $level);
$matrix->generation($users);
$matrix->getArray();
```    

***Обратите внимание***

Каждый массив должен содержать ключи **level** и **number**, на основе которых определяется позиция в матрице.

Можно использовать callback функцию для персональных задач:

```php
$users = [
    [
        'level' => 0,
        'number' => 0,
        'user' => 'Nepster',
    ]
];
$function = function ($l, $n, $user, $matrix) {
    $user['position'] = $matrix->getPosition($user['level'], $user['number']);
    return $user;
};
$matrix = new Matrix($view, $level);
$matrix->generation($users, $function);
$matrix->getArray();
```    


**Все доступные методы**

```php
// Генерация массива матрицы
generation(array $users = [], $callback = null)

// Получить массив матрицы
getArray()

// Вид
getView()

// Кол-во уровней
getLevels()
    
// Получить координаты позиции
getCoordByPosition($position)

// Получить номер позиции
getPosition($level, $number)
    
// Получить координаты первой свободной позиции в матрице
getCoordFirstFreePosition()
    
// Получить все свободные координаты в матрице
getFreeCoords()

// Получить все свободные позиции в матрице
getFreePositions()

// Проверяет заполнена ли матрица
isFilled()

// Деление матрицы
division()
```


**Инструкция**

Для примера в данной библиотеке представлена схема базы данных, которая состоит из 3 таблиц:

    matrix_type - Типы матриц
    
    matrix - Все матрицы
    
    matrix_users - Пользователи в матрицах
    

Чтобы работать с матрицами необходимо создать запись в таблице matrix_type (например Пекет №1 за 10$), создать саму матрицу в таблице matrix и активировать пользователей.
Под активацией пользователей подразумевается записи в таблицу matrix_users. Чтобы показать матрицу на экран, необходимо извлечь массив пользователей 
и сгенерировать матрицу, после чего обратиться к рендеру.

После каждой активации не забывайте проверять заполнена матрица или нет. Это можно сделать вызвав метод **isFilled()**, который вернет true если в матрице 
больше нет свободных позиций. В таком случае вам необходимо вызвать метод **division()** и сохранить новые матрицы в таблицу matrix, также не забудьте после деления 
добавить новые записи в таблицу matrix_users и закрыть старую матрицу присвоив ей определенный статус. 


Рендер матрицы:
---------------

```php
use nepster\matrix\Matrix;
use nepster\matrix\Render;

// Генерация матрицы
$users = [
    [
        'level' => 0,
        'number' => 0,
        'user' => 'Nepster',
    ]
];
$function = function ($l, $n, $user, $matrix) {
    $user['position'] = $matrix->getPosition($user['level'], $user['number']);
    return $user;
};
$matrix = new Matrix(2, 3);
$matrix->generation($users, $function);


// Рендер матрицы
$Render = new Render($matrix);
$Render->setOptions(['class' => 'matrix']);
$Render->setLevelOptions(['class' => 'level']);
$Render->setGroupSeparatorOptions(['class' => 'matrix-group-separator']);
$Render->setClearOptions(['style' => 'clear:both']);
$Render->setGroupJoinOptions(['class' => 'matrix-join-group']);
$Render->registerLevelCallback(function($l, $users) {
    return '<div class="level-counter">Уровень ' . (++$l) . '</div>';
});
$Render->registerCellCallback(function($level, $number, $user, $matrix) {
    return '<div class="cell">
            ' . $matrix->getPosition($level, $number) . '
            <div class="user">
                  Аватар
                  <div class="matrix-user-info">
                    Дополнительная информация
                  </div>
            </div>
            Логин
        </div>';
});
echo $Render->show();
```


**Пример HTML и CSS разметки:**

```
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>MLM Матрица</title>
<style type="text/css">
    .matrix {
        margin:auto;
    }

    .matrix .level {
        width: 680px;
        min-height: 20px;
        margin: 20px auto;
        text-align: center;
        clear: both;
        border: dashed 1px #D3D3D3;
    }

    .matrix .level-counter {
        margin-bottom: 10px;
        display: block;
        text-align: left;
        font-size: 13px;
        font-weight: bold;
        padding: 10px 5px 0 10px;
    }

    .matrix .user {
        width: 45px;
        height: 45px;
        border: double 3px silver;
        overflow: hidden;
        font-size: 13px;
        margin: 5px auto;
    }

    .matrix .user .avatar {
        width: 39px;
        height: 39px;
        overflow: hidden;
    }

    .matrix .user .avatar img{
        width: 39px;
        min-height: 39px;
    }

    .matrix .cell {
        width: 60px;
        display: inline-block;
        border: dashed 1px #D3D3D3;
        margin: 10px 0px;
        padding: 5px 1px 5px 1px;
        overflow: hidden;
        text-align: center;
    }

    .matrix .matrix-join-group {
        display:inline-block;
    }

    .matrix .matrix-group-separator {
        width: 10px;
        display: inline-block;
    }

    .matrix .matrix-user-info {
        display: none
    }

    .matrix .user:hover .matrix-user-info {
        display: block;
        position: absolute;
        width: 200px;
        min-height: 30px;
        border: double 3px silver;
        background: #8BAA79;
        padding: 10px;
        margin-left: -3px;
        margin-top: -3px;
        color: white;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 1px;
    }
</style>

</head>
<body>

    // Рендер матрицы

</body>
</html>
```