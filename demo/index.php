<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixManager;
use Nepster\Matrix\PositionManager;

include '../vendor/autoload.php';


echo '<pre>';

$matrix = new Matrix(2, 3);
//print_r($matrix);

$matrix->addTenant(new Coord(0, 0), function(Coord $coord) {
    return [
        'name' => 'Vasa 0 0'
    ];
});
$matrix->addTenant(new Coord(1, 0), function(Coord $coord) {
    return [
        'name' => 'Peta 1 0'
    ];
});
$matrix->addTenant(new Coord(1, 1), function(Coord $coord) {
    return [
        'name' => 'Olga 1 1'
    ];
});


$matrix->addTenant(new Coord(2, 0), function(Coord $coord) {
    return [
        'name' => 'Vika 2 0'
    ];
});
$matrix->addTenant(new Coord(2, 1), function(Coord $coord) {
    return [
        'name' => 'Sana 2 1'
    ];
});
$matrix->addTenant(new Coord(2, 2), function(Coord $coord) {
    return [
        'name' => 'Kola 2 2'
    ];
});
$matrix->addTenant(new Coord(2, 3), function(Coord $coord) {
    return [
        'name' => 'Bora 2 3'
    ];
});




$matrixManager = new MatrixManager($matrix);
print_r($matrixManager->division());




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


