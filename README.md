Библиотека в разработке...

MLM Matrix
==========

Библиотека для работы с MLM матрицами.


Что такое MLM матрицы ?
-----------------------

В МЛМ в Интернете наиболее популярной формой маркетинг плана является матричный.
Матрицы могут быть разных видов и с разным кол-во уровней. Обычно насчитывается 3 — 4 уровня.
Например кубическая матрица из 3 уровней будет выглядить так:

![alt text](doc/view.png "")

После закрытия матрицы, человек на 1 уровне получает вознаграждение, а МЛМ матрица делится еще на несколько матриц
(зависит от типа, например кубическая матрица разделится еще на 3 новые матрицы). После чего новые матрицы ожидают заполнения
и цикл повторяется.


Установка
---------

Предпочтительный способ установки этого виджета через [composer](http://getcomposer.org/download/).

Запустите в консоле

```
php composer.phar require nepster-web/php-mlm-matrix: dev-master
```

или добавьте

```
"nepster-web/php-mlm-matrix": "dev-master"
```

в файл `composer.json` в секцию require.


Структура:
----------

**Matrix.php** - Библиотека для работы с матрицами.

**Render.php** - Генератор html кода матрицы.

**shema/matrix.sql** - SQL (MySql) Схема таблиц для матриц.


Matrix.php
----------

 **generation($view, $levels, array $users, $callback = null)** - Генерация массива матрицы исходя из вида и ровней.

 **getCoordByPosition($position, $view, $levels)** - Получить координаты (уровень и номер) позиции в матрице.

 **getPosition($level, $number, $view)** - Получить позицию в матрице.

 **getCoordFirstFreePosition(array $matrix)** - Получить координаты первой свободной позиции.

 **isFilled(array $matrix)** - Проверяет заполнена ли матрица.

 **division()** - Деление матрицы.


Пример использования:
---------------------

**Рендер матрицы:**

```php
    use nepster\matrix\Matrix;
    use nepster\matrix\Render;


    // Генерация новой матрицы
    $view = 2;
    $levels = 4;
    $users = [];
    $matrix = Matrix::generation($view, $levels, $users);

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
    $Render->registerCellCallback(function($l, $n, $user) use ($view) {
        return '<div class="cell">
                    ' . Matrix::getPosition($l, $n, $view) . '
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