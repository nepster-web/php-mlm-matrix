<?php

namespace Nepster\Matrix;

/**
 * Class MatrixManager
 *
 * @package Nepster\Matrix
 */
class MatrixManager
{
    /**
     * @var Matrix
     */
    private $matrix;

    /**
     * MatrixManager constructor.
     * @param Matrix $matrix
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }

    /**
     * @return array
     */
    public function division(): array
    {
        $matrices = [];
        $pointer = 1;

        for ($m = 1; $m < $this->matrix->getDepth(); ++$m) {
            $matrices[] = array_chunk($this->matrix->toArray()[$m], $pointer);
            $pointer *= $this->matrix->getPow();
        }

        $countMatrices = count($matrices);

        $newMatrixArrayList = [];

        $x = 0;

        for ($m = 0; $m < $countMatrices; ++$m) {
            $countMatrixDepth = count($matrices[$m]);
            for ($n = 0; $n < $countMatrixDepth; ++$n, ++$x) {
                $newMatrixArrayList[$x][] = $matrices[$m][$n];
            }
            $x = 0;
        }

        $newMatrixList = [];

        foreach ($newMatrixArrayList as $m => $newMatrixArray) {
            $newMatrixList[$m] = new Matrix($this->matrix->getDepth(), $this->matrix->getPow());
            foreach ($newMatrixArray as $d => $depth) {
                foreach ($depth as $n => $number) {
                    $newMatrixList[$m]->addTenant(new Coord($d, $n), function () use ($d, $n, $newMatrixArray) {
                        if (isset($newMatrixArray[$d][$n]) && empty($newMatrixArray[$d][$n]) === false) {
                            return $newMatrixArray[$d][$n];
                        }
                        return false;
                    });
                }

            }

        }

        return $newMatrixList;
    }
}
