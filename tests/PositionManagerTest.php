<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\PositionManager;

/**
 * Class PositionManagerTest
 */
class PositionManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function testConvertPositionToCoord(): void
    {
        $matrix = new Matrix(3, 2);
        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->positionToCoord(1), new Coord(0, 0));
        self::assertEquals($positionManager->positionToCoord(2), new Coord(1, 0));
        self::assertEquals($positionManager->positionToCoord(3), new Coord(1, 1));
        self::assertEquals($positionManager->positionToCoord(4), new Coord(2, 0));
        self::assertEquals($positionManager->positionToCoord(5), new Coord(2, 1));

        self::assertEquals($positionManager->positionToCoord(-100), null);
        self::assertEquals($positionManager->positionToCoord(100), null);
    }

    /** @test */
    public function testConvertCoordToPosition(): void
    {
        $matrix = new Matrix(3, 2);
        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->coordToPosition(new Coord(0, 0)), 1);
        self::assertEquals($positionManager->coordToPosition(new Coord(1, 0)), 2);
        self::assertEquals($positionManager->coordToPosition(new Coord(1, 1)), 3);
        self::assertEquals($positionManager->coordToPosition(new Coord(2, 0)), 4);
        self::assertEquals($positionManager->coordToPosition(new Coord(2, 1)), 5);

        self::assertEquals($positionManager->coordToPosition(new Coord(-1, -1)), null);
        self::assertEquals($positionManager->coordToPosition(new Coord(100, 100)), null);
    }

    /** @test */
    public function testFindFirstFreeCoord(): void
    {
        $matrix = new Matrix(3, 2);
        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findFirstFreeCoord(), new Coord(0, 0));
    }

    /** @test */
    public function testFindFirstFreeCoordInNotEmptyMatrix(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John'];
        });

        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findFirstFreeCoord(), new Coord(1, 0));
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

        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findFirstFreeCoord(), null);
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

        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findFreeCoords(), [
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

        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findFreePositions(), [3, 4, 5, 7]);
    }

    /** @test */
    public function testFindSectorCoordsForNextDepth(): void
    {
        $matrix = new Matrix(3, 2);

        $positionManager = new PositionManager($matrix);

        self::assertEquals($positionManager->findSectorCoordsForNextDepth(new Coord(1, 1)), [
            new Coord(2, 2),
            new Coord(2, 3)
        ]);
    }
}
