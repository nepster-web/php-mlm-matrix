<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixManager;

include '../vendor/autoload.php';


echo '<pre>';

$matrix = new Matrix(2, 3);
print_r($matrix);
var_dump($matrix->isFilled());

/*

echo '<hr>';

$matrixFinder = new CellFinder($matrix);
print_r($matrixFinder->findCoordByPosition(3));


echo '<hr>';

print_r($matrixFinder->findPosition(new Coord(0, 0)));


echo '<hr>';

print_r($matrixFinder->findFirstFreeCoord());


echo '<hr>';

print_r($matrixFinder->findFreeCoords());




echo '<hr>';

print_r($matrixFinder->findFreePositions());

*/

echo '<hr>';

$matrixFinder = new MatrixManager($matrix);
print_r($matrixFinder->findParentsCoords(new Coord(2, 3)));




echo '<hr>';

//print_r($matrixFinder->findCoordBySector(2, 3, 1));

