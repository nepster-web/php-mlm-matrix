MLM Matrix
==========

[![Latest Version](https://img.shields.io/github/tag/nepster-web/php-mlm-matrix.svg?style=flat-square&label=release)](https://github.com/nepster-web/php-mlm-matrix)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/nepster-web/php-mlm-matrix.svg?style=flat-square)](https://packagist.org/packages/nepster-web/php-mlm-matrix)

Library for working with MLM matrices.


What is the MLM matrix?
-----------------------

Among the many MLM compensation plans available today, the Matrix plan is among the most popularly 
recommended owing to its uncomplicated structure. As it is quite simple in understanding it is 
considered very useful and resourceful and can be easily integrated into the MLM business.

To understand the Matrix plan, it makes sense to first understand its structure. The matrix  
has fixed numbers of rows and columns, organizing the numbers in a particular width and depth. 
Typically, most MLM Matrix plans follow two types of structures; 2x2 or the 3x3, but there are 
exceptions based on company requirements. All the members in a Matrix Plan are positioned 
serially from top to bottom or left to right.

![demo](./doc/images/view.png "")

После того, как матрица будет заполнена, человек на 1 уровне получает вознаграждение, а сама матрица делится еще на несколько матриц
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

* `demo` - Library demo
* `doc` - Documentation files for GitHub
* `shema` - Sample database table schema (MySQL)
* `src` - Main library code
* `tests` - Unit tests



Usage
-----

Creating a new matrix object:
```php
use Nepster\Matrix\Matrix;

$matrix = new Matrix(3, 2);
```


Getting information about the matrix:
```php
$matrix->getDepth();
$matrix->getPow();
```


Get matrix array:
```php
$matrix->toArray();
```


Managing users in the matrix:
```php
use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;

$matrix = new Matrix(3, 2);

$matrix->addTenant(null, function() {
    // return your user data
})

$matrix->addTenant(new Coord(1, 1), function() {
    // return your user data
})

$matrix->hasTenant(new Coord(0, 0));
$matrix->hasTenant(new Coord(1, 1));

$matrix->removeTenant(new Coord(1, 1));
```


Check the correctness of coordinates:
```php
$matrix->isValidCoord(new Coord(0, 0));
```


Check if there are free positions in the matrix:
```php
$matrix->isFilled();
```

Testing
-------

```$ phpunit```


License
-------
This library is licensed under the MIT License - see the LICENSE file for details.