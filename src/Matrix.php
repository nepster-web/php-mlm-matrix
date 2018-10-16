<?php

namespace Nepster\Matrix;

use Nepster\Matrix\Exception\MatrixException;
use Nepster\Matrix\Exception\FilledMatrixException;
use Nepster\Matrix\Exception\UnavailablePositionException;
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
        for ($d = 0; $d < $this->depth; ++$d) {
            for ($n = 0; $n < $pointer; ++$n) {
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
     * Checks if matrix position is free
     *
     * @param Coord $coord
     * @return bool
     * @throws IncorrectCoordinatesMatrixException
     */
    public function hasTenant(Coord $coord): bool
    {
        if ($this->isValidCoord($coord) === false) {
            throw new IncorrectCoordinatesMatrixException();
        }

        foreach ($this->generatedMatrix as $d => $depth) {
            if ($d === $coord->getDepth()) {
                foreach ($depth as $n => $number) {
                    if ($coord->getNumber() === $n) {
                        if ($this->generatedMatrix[$d][$n] === null) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Take this matrix position
     *
     * @param Coord|null $coord
     * @param callable $tenant
     * @throws FilledMatrixException
     * @throws IncorrectCoordinatesMatrixException
     * @throws MatrixException
     * @throws UnavailablePositionException
     */
    public function addTenant(?Coord $coord, callable $tenant): void
    {
        if ($this->isFilled() === true) {
            throw new FilledMatrixException();
        }

        if ($coord !== null && $this->isValidCoord($coord) === false) {
            throw new IncorrectCoordinatesMatrixException();
        }

        if ($coord !== null && $this->hasTenant($coord) === true) {
            throw new UnavailablePositionException();
        }

        if ($coord === null) {
            foreach ($this->generatedMatrix as $d => $depth) {
                foreach ($depth as $n => $number) {
                    if ($number === null) {
                        $result = call_user_func_array($tenant, [new Coord($d, $n)]);
                        if ($result === null || (empty($result) && $result !== false)) {
                            throw new MatrixException('The callable argument $tenant should not return null or be empty');
                        }
                        $this->generatedMatrix[$d][$n] = $result;
                        return;
                    }
                }
            }
        } else {
            foreach ($this->generatedMatrix as $d => $depth) {
                if ($d === $coord->getDepth()) {
                    foreach ($depth as $n => $number) {
                        if ($coord->getNumber() === $n) {
                            $result = call_user_func_array($tenant, [$coord]);
                            if ($result === null || (empty($result) && $result !== false)) {
                                throw new MatrixException('The callable argument $tenant should not return null or be empty');
                            }
                            $this->generatedMatrix[$d][$n] = $result;
                            return;
                        }
                    }
                }
            }
        }
    }

    /**
     * Free this matrix position
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
                    if ($number === null) {
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

        if (isset($this->generatedMatrix[$coord->getDepth()]) === false) {
            return false;
        }

        if ($coord->getNumber() > (count($this->generatedMatrix[$coord->getDepth()]) - 1)) {
            return false;
        }

        return true;
    }
}
