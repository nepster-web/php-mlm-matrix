MLM Matrix
==========

[![Latest Version](https://img.shields.io/github/tag/nepster-web/php-mlm-matrix.svg?style=flat-square&label=release)](https://github.com/nepster-web/php-mlm-matrix)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/nepster-web/php-mlm-matrix.svg?style=flat-square)](https://packagist.org/packages/nepster-web/php-mlm-matrix)

Library for working with MLM matrices.


Did you find an error?
-----------------------
If you found an error in the code or grammatical mistake or any inaccuracy, 
please create [new issues](https://github.com/nepster-web/php-mlm-matrix/issues/new).


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

After the matrix is filled, user at level 1 receives a reward and the matrix itself is divided into 
several matrices (depends on matrix pow, for example the cubic matrix will be divided into 3 new matrices). 
After that, new matrices wait for filling and cycle is repeated.


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

[For more examples, see the demo file.](./demo/index.php)


How can I use database for matrices? 
------------------------------------

Based on the different specifics of mlm projects and web development tools,
this library implements only the algorithm of operation of mlm matrices without storage support.

However, if you works with the database you can easily implement keeping and restore of matrix objects.
You can study the example in the file [MySQL schema](shema/matrix.sql). 

For example, create a new service that allows you to write and/or restore the matrix object from the database:

```php
class MatrixService {

    public function findById(int $id): Matrix
    {
        // You need make a query to the `matrix` table that find the required record
        // Use join or another query to retrieve user data from the `matrix_users` table
        // Initialize new Matrix object
        // Using `addTenant` method that add users to Matrix object (based on data from `matrix_users` table)
        // Return the proper Matrix object
    }

    public function save(Matrix $matrix): void
    {
        // Get the matrix array using the `$matrix->toArray()` method
        // Create a valid request to save data to the database
        // - Most likely in a relational database you will have 2 tables (matrices and matrix_users)
        // - Don`t forget to check a new matrix is being created or edited an already existing matrix
        
        // Note1: Write down users with depth and number for further recovery
        // Note2: Don`t write coordinates with empty positions to the database.
    }

}
``` 


Testing
-------

```$ phpunit```

or 

```$ vendor/bin/phpunit```


License
-------
This library is licensed under the MIT License - see the LICENSE file for details.