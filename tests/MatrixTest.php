<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;

/**
 * Class MatrixTest
 */
class MatrixTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function testItReturnsCurrentDepth(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertEquals($matrix->getDepth() ,3);
    }

    /** @test */
    public function testItReturnsCurrentPow(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertEquals($matrix->getPow() ,2);
    }

    /** @test */
    public function testItReturnsCurrentMatrixArrayWithDepth3AndPow2(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertEquals($matrix->toArray() ,[
            [null],
            [null, null],
            [null, null, null, null]
        ]);
    }

    /** @test */
    public function testItReturnsCurrentMatrixArrayWithDepth4AndPow2(): void
    {
        $matrix = new Matrix(4, 2);

        self::assertEquals($matrix->toArray() ,[
            [null],
            [null, null],
            [null, null, null, null],
            [null, null, null, null, null, null, null, null]
        ]);
    }

    /** @test */
    public function testItReturnsCurrentMatrixArrayWithDepth3AndPow3(): void
    {
        $matrix = new Matrix(3, 3);

        self::assertEquals($matrix->toArray() ,[
            [null],
            [null, null, null],
            [null, null, null, null, null, null, null, null, null]
        ]);
    }

    /** @test */
    public function testItReturnsCurrentMatrixArrayWithDepth4AndPow3(): void
    {
        $matrix = new Matrix(4, 3);

        self::assertEquals($matrix->toArray() ,[
            [null],
            [null, null, null],
            [null, null, null, null, null, null, null, null, null],
            [
                null, null, null, null, null, null, null, null, null,
                null, null, null, null, null, null, null, null, null,
                null, null, null, null, null, null, null, null, null,
            ]
        ]);
    }

    /** @test */
    public function testCheckValidCoord(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertTrue($matrix->isValidCoord(new Coord(0, 0)));
    }

    /** @test */
    public function testCheckInValidCoord(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertFalse($matrix->isValidCoord(new Coord(0, 1)));
    }
}
