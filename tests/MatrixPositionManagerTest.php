<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixPositionManager;

/**
 * Class MatrixPositionManagerTest
 */
class MatrixPositionManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function testConvertPositionToCoord(): void
    {
        $matrix = new Matrix(3, 2);
        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->positionToCoord(1), new Coord(0, 0));
        self::assertEquals($MatrixPositionManager->positionToCoord(2), new Coord(1, 0));
        self::assertEquals($MatrixPositionManager->positionToCoord(3), new Coord(1, 1));
        self::assertEquals($MatrixPositionManager->positionToCoord(4), new Coord(2, 0));
        self::assertEquals($MatrixPositionManager->positionToCoord(5), new Coord(2, 1));

        self::assertEquals($MatrixPositionManager->positionToCoord(-100), null);
        self::assertEquals($MatrixPositionManager->positionToCoord(100), null);
    }

    /** @test */
    public function testConvertCoordToPosition(): void
    {
        $matrix = new Matrix(3, 2);
        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(0, 0)), 1);
        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(1, 0)), 2);
        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(1, 1)), 3);
        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(2, 0)), 4);
        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(2, 1)), 5);

        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(-1, -1)), null);
        self::assertEquals($MatrixPositionManager->coordToPosition(new Coord(100, 100)), null);
    }

    /** @test */
    public function testFindFirstFreeCoord(): void
    {
        $matrix = new Matrix(3, 2);
        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findFirstFreeCoord(), new Coord(0, 0));
    }

    /** @test */
    public function testFindFirstFreeCoordInNotEmptyMatrix(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John'];
        });

        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findFirstFreeCoord(), new Coord(1, 0));
    }

    /** @test */
    public function testFindFirstFreeCoordInFilledMatrix(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John1'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John2'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John3'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John4'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John5'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John6'];
        });
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John7'];
        });

        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findFirstFreeCoord(), null);
    }

    /** @test */
    public function testFindFreeCoords(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(new Coord(0, 0), function (): array {
            return ['name' => 'John1'];
        });
        $matrix->addTenant(new Coord(1, 0), function (): array {
            return ['name' => 'John2'];
        });
        $matrix->addTenant(new Coord(2, 2), function (): array {
            return ['name' => 'John3'];
        });
        $matrix->addTenant(new Coord(2, 3), function (): array {
            return ['name' => 'John4'];
        });

        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findFreeCoords(), [
            new Coord(1, 1),
            new Coord(2, 0),
            new Coord(2, 1),
        ]);
    }

    /** @test */
    public function testFindFreePositions(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(new Coord(0, 0), function (): array {
            return ['name' => 'John1'];
        });
        $matrix->addTenant(new Coord(1, 0), function (): array {
            return ['name' => 'John2'];
        });
        $matrix->addTenant(new Coord(2, 2), function (): array {
            return ['name' => 'John3'];
        });

        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findFreePositions(), [3, 4, 5, 7]);
    }

    /** @test */
    public function testFindSectorCoordsForNextDepth(): void
    {
        $matrix = new Matrix(3, 2);

        $MatrixPositionManager = new MatrixPositionManager($matrix);

        self::assertEquals($MatrixPositionManager->findSectorCoordsForNextDepth(new Coord(1, 1)), [
            new Coord(2, 2),
            new Coord(2, 3)
        ]);
    }
}
