<?php

use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixManager;

/**
 * Class MatrixManagerTest
 */
class MatrixManagerTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function testMatrixDivision(): void
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

        $matrixManager = new MatrixManager($matrix);

        $matrices = $matrixManager->division();

        /** @var Matrix $matrix1 */
        $matrix1 = $matrices[0];

        /** @var Matrix $matrix2 */
        $matrix2 = $matrices[1];

        self::assertEquals($matrix1->toArray(), [
            [['name' => 'John2']],
            [['name' => 'John4'], ['name' => 'John5']],
            [null, null, null, null]
        ]);
        self::assertEquals($matrix2->toArray(), [
            [['name' => 'John3']],
            [['name' => 'John6'], ['name' => 'John7']],
            [null, null, null, null]
        ]);
    }

    /** @test */
    public function testEmptyMatrixDivision(): void
    {
        $matrix = new Matrix(3, 2);
        $matrix->addTenant(null, function (): array {
            return ['name' => 'John2'];
        });

        $matrixManager = new MatrixManager($matrix);

        $matrices = $matrixManager->division();

        /** @var Matrix $matrix1 */
        $matrix1 = $matrices[0];

        /** @var Matrix $matrix2 */
        $matrix2 = $matrices[1];

        self::assertEquals($matrix1->toArray(), [
            [false],
            [false, false],
            [null, null, null, null]
        ]);
        self::assertEquals($matrix2->toArray(), [
            [false],
            [false, false],
            [null, null, null, null]
        ]);
    }
}
