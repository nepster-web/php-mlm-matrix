<?php

namespace Nepster\Matrix;

/**
 * Class Matrix
 *
 * @package Nepster\Matrix
 */
class Matrix
{
    /**
     * Pointer to the power (binary matrix, cubic matrix etc.)
     * Don't specify a large number (it is recommended 2 or 3)
     * @var int
     */
    private $pow;

    /**
     * Number of levels
     * Don't specify a large number (it is recommended 3, 4 or 5)
     * @var int
     */
    private $depth;

    /**
     * @var array
     */
    private $generatedMatrix;

    /**
     * Matrix constructor.
     * @param int $pow
     * @param int $depth
     */
    public function __construct(int $pow = 2, int $depth = 4)
    {
        $this->pow = $pow;
        $this->depth = $depth;

        $matrix = [];
        $pointer = 1;
        for ($l = 0; $l < $this->depth; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                $matrix[$l][$n] = null;
            }
            $pointer *= $this->pow;
        }

        $this->generatedMatrix = $matrix;
    }

    /**
     * @return int
     */
    public function getPow(): int
    {
        return $this->pow;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->generatedMatrix;
    }

    /**
     * Is the matrix of the filled
     *
     * @return bool
     */
    public function isFilled(): bool
    {
        foreach ($this->generatedMatrix as $d => $depth) {
            if (is_array($depth)) {
                foreach ($depth as $n => $number) {
                    if (empty($number)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

}
