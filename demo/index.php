<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\CellFinder;

include '../vendor/autoload.php';


echo '<pre>';

$matrix = new Matrix(2, 3);
print_r($matrix);
var_dump($matrix->isFilled());

echo '<hr>';

$matrixFinder = new CellFinder($matrix);
print_r($matrixFinder->getCoordByPosition(1));


echo '<hr>';

print_r($matrixFinder->getPosition(new Coord(0, 0)));


echo '<hr>';

print_r($matrixFinder->getFirstFreeCoord());


echo '<hr>';

print_r($matrixFinder->getFreeCoords());




echo '<hr>';

print_r($matrixFinder->getFreePositions());



echo '<hr>';

print_r($matrixFinder->getParentsCoords(new Coord(2, 3)));




echo '<hr>';

print_r($matrixFinder->getCoordBySector(2, 3, 1));

