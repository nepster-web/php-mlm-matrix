<?php

namespace Nepster\Matrix;

use Nepster\Matrix\Exception\FilledMatrixException;
use Nepster\Matrix\Exception\IncorrectCoordinatesMatrixException;

/**
 * Class Matrix
 *
 * @package Nepster\Matrix
 */
class Matrix
{
    /**
     * Number of levels
     * Don't specify a large number (it is recommended 3 or 4)
     * @var int
     */
    private $depth;

    /**
     * Pointer to the power (binary matrix, cubic matrix etc.)
     * Don't specify a large number (it is recommended 2 or 3)
     * @var int
     */
    private $pow;

    /**
     * @var array
     */
    private $generatedMatrix;

    /**
     * Matrix constructor.
     * @param int $depth
     * @param int $pow
     */
    public function __construct(int $depth = 4, int $pow = 2)
    {
        $this->pow = $pow;
        $this->depth = $depth;

        $matrix = [];
        $pointer = 1;
        for ($d = 0; $d < $this->depth; $d++) {
            for ($n = 0; $n < $pointer; $n++) {
                $matrix[$d][$n] = null;
            }
            $pointer *= $this->pow;
        }

        $this->generatedMatrix = $matrix;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @return int
     */
    public function getPow(): int
    {
        return $this->pow;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->generatedMatrix;
    }

    /**
     * Take a position in the matrix
     *
     * @param Coord $coord
     * @param callable $tenant
     * @throws FilledMatrixException
     * @throws IncorrectCoordinatesMatrixException
     */
    public function addTenant(Coord $coord, callable $tenant): void
    {
        if ($this->isFilled() === true) {
            throw new FilledMatrixException();
        }

        if ($this->isValidCoord($coord) === false) {
            throw new IncorrectCoordinatesMatrixException();
        }

        foreach ($this->generatedMatrix as $d => $depth) {
            if ($d === $coord->getDepth()) {
                foreach ($depth as $n => $number) {
                    if ($coord->getNumber() === $n) {
                        $this->generatedMatrix[$d][$n] = call_user_func_array($tenant, [$coord]);
                        break;
                    }
                }
            }
        }
    }

    /**
     * Free the position in the matrix
     *
     * @param Coord $coord
     * @throws IncorrectCoordinatesMatrixException
     */
    public function removeTenant(Coord $coord): void
    {
        if ($this->isValidCoord($coord) === false) {
            throw new IncorrectCoordinatesMatrixException();
        }

        foreach ($this->generatedMatrix as $d => $depth) {
            if ($d === $coord->getDepth()) {
                foreach ($depth as $n => $number) {
                    if ($coord->getNumber() === $n) {
                        $this->generatedMatrix[$d][$n] = null;
                        break;
                    }
                }
            }
        }
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

    /**
     * Checks the coordinates for validity
     *
     * @param Coord $coord
     * @return bool
     */
    public function isValidCoord(Coord $coord): bool
    {
        if ($coord->getDepth() < 0 || $coord->getDepth() > $this->depth) {
            return false;
        }

        if ($coord->getNumber() < 0) {
            return false;
        }

        if ($coord->getNumber() > (count($this->generatedMatrix[$coord->getDepth()]) - 1)) {
            return false;
        }

        return true;
    }
}
