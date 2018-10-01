<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixManager;
use Nepster\Matrix\PositionManager;

include '../vendor/autoload.php';


echo '<pre>';

$matrix = new Matrix(2, 2);
//print_r($matrix);


$matrix->addTenant(null, function(Coord $coord) {
    return [
        'name' => 'Vasa 0 0'
    ];
});


$matrix->addTenant(new Coord(1, 0), function(Coord $coord) {
    return [
        'name' => 'Vasa 0 0'
    ];
});


$matrix->addTenant(null, function(Coord $coord) {
    return [
        'name' => 'Vasa 0 0'
    ];
});


print_r($matrix->toArray());




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

echo '<hr>';;


