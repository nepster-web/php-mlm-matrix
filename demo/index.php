<?php

use Nepster\Matrix\Matrix;

include '../vendor/autoload.php';


echo '<pre>';

$matrix = new Matrix(2, 4);
print_r($matrix->generation());