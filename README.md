MLM Matrix
==========

[![Latest Version](https://img.shields.io/github/tag/nepster-web/php-mlm-matrix.svg?style=flat-square&label=release)](https://github.com/nepster-web/php-mlm-matrix)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/nepster-web/php-mlm-matrix.svg?style=flat-square)](https://packagist.org/packages/nepster-web/php-mlm-matrix)

Library for working with MLM matrices.


Что такое MLM матрицы ?
-----------------------

В МЛМ в Интернете наиболее популярной формой маркетинг плана является матричный.
Матрицы могут быть разных видов и с разным кол-во уровней. Обычно насчитывается 3 — 4 уровня.
Например кубическая матрица из 3 уровней будет выглядить так:

![demo](./doc/images/view.png "")

После закрытия матрицы, человек на 1 уровне получает вознаграждение, а МЛМ матрица делится еще на несколько матриц
(зависит от типа, например кубическая матрица разделится еще на 3 новые матрицы). После чего новые матрицы ожидают заполнения
и цикл повторяется.


Install
-------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require --prefer-dist nepster-web/php-mlm-matrix "*"
```

or add

```
"nepster-web/php-mlm-matrix": "*"
```

to the `require` section of your `composer.json` file.



Structure
---------

* `demo` - Демонстрационный пример работы библиотеки
* `doc` - Файлы документации для GitHub
* `shema` - Пример схемы таблиц для MySQL
* `src` - Код самой библиотеки
* `tests` - Unit tests



Usage
-----



Testing
-------

```$ phpunit```