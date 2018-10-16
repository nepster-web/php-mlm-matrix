<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixRender;
use Nepster\Matrix\MatrixManager;
use Nepster\Matrix\MatrixPositionManager;

include '../vendor/autoload.php';


$matrix = new Matrix(3, 2);
$matrixPositionManager = new MatrixPositionManager($matrix);

$matrix->addTenant(null, function (Coord $coord): array {
    return [
        'name' => 'Superman',
        'avatar' => 'images/avatar-superman.jpg',
    ];
});

$matrix->addTenant(null, function (Coord $coord): array {
    return [
        'name' => 'Diana',
        'avatar' => 'images/avatar-wonder-woman.jpg',
    ];
});

$matrix->addTenant(null, function (Coord $coord): array {
    return [
        'name' => 'The Flash',
        'avatar' => 'images/avatar-flash.jpg',
    ];
});

$matrix->addTenant($matrixPositionManager->positionToCoord(6), function (Coord $coord): array {
    return [
        'name' => 'Batman',
        'avatar' => 'images/avatar-batman.jpg',
    ];
});


// Matrix Render
$render = new MatrixRender($matrix);
$render->setOptions(['class' => 'matrix']);
$render->setDepthOptions(['class' => 'depth']);
$render->setGroupSeparatorOptions(['class' => 'matrix-group-separator']);
$render->setClearOptions(['style' => 'clear:both']);
$render->setGroupJoinOptions(['class' => 'matrix-join-group']);
$render->registerDepthCallback(function (Matrix $matrix, int $d, array $tenants): string {
    return '<div class="depth-counter">Depth ' . (++$d) . '</div>';
});
$render->registerCellCallback(function (Matrix $matrix, Coord $coord, $tenant) use ($matrixPositionManager): string {
    if ($tenant === null) {
        return '<div class="cell">
            ' . $matrixPositionManager->coordToPosition($coord) . '
            <div class="user">
                  <div class="avatar" style="background-image: url(images/free.jpg)"></div>
            </div>
            <div style="color: silver">free</div>
        </div>';
    }

    return '<div class="cell">
        ' . $matrixPositionManager->coordToPosition($coord) . '
        <div class="user">
              <div class="avatar" style="background-image: url(' . $tenant['avatar'] . ')"></div>
              <div class="matrix-user-info">
                Extra info
              </div>
        </div>
        <div class="user-name">' . $tenant['name'] . '</div>
    </div>';
});

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>MLM Matrix</title>
    <style type="text/css">

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .matrix {
            margin: auto;
            font-size: 12px;
        }

        .matrix .depth {
            width: 680px;
            min-height: 20px;
            margin: 20px auto;
            text-align: center;
            clear: both;
            border: dashed 1px #D3D3D3;
        }

        .matrix .depth-counter {
            margin-bottom: 10px;
            display: block;
            text-align: left;
            font-weight: bold;
            padding: 10px 5px 0 10px;
        }

        .matrix .user {
            width: 45px;
            height: 45px;
            border: double 3px silver;
            overflow: hidden;
            margin: 5px auto;
        }

        .matrix .user .avatar {
            width: 45px;
            height: 45px;
            background-size: cover;
            overflow: hidden;
        }

        .matrix .user-name {
            white-space: nowrap;
        }

        .matrix .cell {
            width: 60px;
            display: inline-block;
            border: dashed 1px #D3D3D3;
            margin: 10px 0;
            padding: 5px 1px 5px 1px;
            overflow: hidden;
            text-align: center;
        }

        .matrix .matrix-join-group {
            display: inline-block;
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
            font-weight: bold;
            letter-spacing: 1px;
        }
    </style>

</head>
<body>

<?= $render->show() ?>

</body>
</html>