<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\Exception\FilledMatrixException;

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
    public function testCheckValidCoordWithRandDepth(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertTrue($matrix->isValidCoord(new Coord(2, 0)));
    }

    /** @test */
    public function testCheckValidCoordWithRandPow(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertTrue($matrix->isValidCoord(new Coord(2, 3)));
    }

    /** @test */
    public function testCheckInValidCoord(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertFalse($matrix->isValidCoord(new Coord(0, 1)));
    }

    /** @test */
    public function testCheckInValidCoordWithLongDepth(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertFalse($matrix->isValidCoord(new Coord(10, 1)));
    }

    /** @test */
    public function testCheckInValidCoordWithRandDepth(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertFalse($matrix->isValidCoord(new Coord(5, 1)));
    }

    /** @test */
    public function testCheckInValidCoordWithRandPow(): void
    {
        $matrix = new Matrix(3, 2);

        self::assertFalse($matrix->isValidCoord(new Coord(2, 7)));
    }

    /** @test */
    public function testCheckThatMatrixIsNotFilled(): void
    {
        $matrix = new Matrix(2, 2);

        self::assertFalse($matrix->isFilled());
    }

    /** @test */
    public function testCheckThatMatrixIsFilled(): void
    {
        $this->expectException(FilledMatrixException::class);

        $matrix = new Matrix(2, 2);

        $matrix->addTenant(null, function (): string { return uniqid();});
        $matrix->addTenant(null, function (): string { return uniqid();});
        $matrix->addTenant(null, function (): string { return uniqid();});
        $matrix->addTenant(null, function (): string { return uniqid();});
    }

    /** @test */
    public function testItsAddTenantToFirstFreePosition1(): void
    {
        $matrix = new Matrix(2, 2);

        $matrix->addTenant(null, function (): string { return uniqid();});

        self::assertTrue($matrix->hasTenant(new Coord(0, 0)));
    }

    /** @test */
    public function testItsAddTenantToFirstFreePosition2(): void
    {
        $matrix = new Matrix(2, 2);

        $matrix->addTenant(null, function (): string { return uniqid();});
        $matrix->addTenant(null, function (): string { return uniqid();});

        self::assertTrue($matrix->hasTenant(new Coord(0, 0)));
        self::assertTrue($matrix->hasTenant(new Coord(1, 0)));
    }

    /** @test */
    public function testItsAddTenantToSelectPosition(): void
    {
        $matrix = new Matrix(3, 2);

        $matrix->addTenant(new Coord(2, 1), function (): string { return uniqid();});

        self::assertTrue($matrix->hasTenant(new Coord(2, 1)));
    }

    /** @test */
    public function testItsHasTenant(): void
    {
        $matrix = new Matrix(3, 2);

        $matrix->addTenant(new Coord(2, 1), function (): string { return uniqid();});

        self::assertTrue($matrix->hasTenant(new Coord(2, 1)));
        self::assertFalse($matrix->hasTenant(new Coord(0, 0)));
        self::assertFalse($matrix->hasTenant(new Coord(1, 0)));
        self::assertFalse($matrix->hasTenant(new Coord(1, 1)));
    }

    /** @test */
    public function testItsRemoveTenant(): void
    {
        $matrix = new Matrix(3, 2);

        $matrix->addTenant(new Coord(2, 1), function (): string { return uniqid();});

        self::assertTrue($matrix->hasTenant(new Coord(2, 1)));

        $matrix->removeTenant(new Coord(2, 1));

        self::assertFalse($matrix->hasTenant(new Coord(2, 1)));
    }

}
