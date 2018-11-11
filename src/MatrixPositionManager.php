<?php

namespace Nepster\Matrix;

/**
 * Class MatrixPositionManager
 *
 * @package Nepster\Matrix
 */
class MatrixPositionManager
{
    /**
     * @var Matrix
     */
    private $matrix;

    /**
     * PositionManager constructor.
     * @param Matrix $matrix
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }

    /**
     * Converts position to coordinates
     *
     * For example:
     * position: 1 - [depth: 0, number 0]
     * position: 2 - [depth: 1, number 0]
     * position: 3 - [depth: 1, number 1]
     *
     * returns null if the position does not match the coordinates
     *
     * @param int $position
     * @return Coord|null
     */
    public function positionToCoord(int $position): ?Coord
    {
        $result = 0;
        $pointer = 1;
        for ($d = 0; $d < $this->matrix->getDepth(); ++$d) {
            for ($n = 0; $n < $pointer; ++$n) {
                ++$result;
                if ($result === $position) {
                    return new Coord($d, $n);
                }
            }
            $pointer *= $this->matrix->getPow();
        }

        return null;
    }

    /**
     * Converts coordinates to position
     *
     * For example:
     * [depth: 0, number 0] - position: 1
     * [depth: 1, number 0] - position: 2
     * [depth: 1, number 1] - position: 3
     *
     * returns null if the coordinates are outside the matrix
     *
     * @param Coord $coord
     * @return int|null
     */
    public function coordToPosition(Coord $coord): ?int
    {
        if ($coord->getDepth() === 0 && $coord->getNumber() === 0) {
            return 1;
        }

        if ($coord->getDepth() > $this->matrix->getDepth()) {
            return null;
        }

        $position = 0;
        $pointer = 1;
        for ($d = 0; $d < $this->matrix->getDepth(); ++$d) {
            for ($n = 0; $n < $pointer; ++$n) {
                ++$position;
                if ($d === $coord->getDepth() && $n === $coord->getNumber()) {
                    return $position;
                }
            }
            $pointer *= $this->matrix->getPow();
        }

        return null;
    }

    /**
     * Find first free matrix coordinates
     * @return Coord|null
     */
    public function findFirstFreeCoord(): ?Coord
    {
        foreach ($this->matrix->toArray() as $d => $depth) {
            if (is_array($depth)) {
                foreach ($depth as $n => $number) {
                    if (empty($number)) {
                        return new Coord($d, $n);
                    }
                }
            }
        }

        return null;
    }

    /**
     * Find all free coordinates in the matrix
     * @return array
     */
    public function findFreeCoords(): array
    {
        $result = [];
        foreach ($this->matrix->toArray() as $l => &$level) {
            if (is_array($level)) {
                foreach ($level as $n => &$number) {
                    if (empty($number)) {
                        $result[] = new Coord($l, $n);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Find all free positions in the matrix
     * @return array
     */
    public function findFreePositions(): array
    {
        $result = [];
        $freeCoords = $this->findFreeCoords();

        /** @var Coord $coord */
        foreach ($freeCoords as $coord) {
            $result[] = $this->coordToPosition(new Coord($coord->getDepth(), $coord->getNumber()));
        }

        return $result;
    }

    /**
     * Returns the coordinates of all sector positions for the current position
     *
     * Пример использования:
     * $matrix->getCoordBySector($level, $number, $deep, $all)
     *
     * @param Coord $coord
     * @return array
     */
    public function findSectorCoordsForNextDepth(Coord $coord): array
    {
        if ($this->matrix->isValidCoord($coord) === false) {
            return [];
        }

        $number = [$coord->getNumber()];

        $newChildrenLevel = [];
        foreach ($number as $pos) {
            $newChildrenLevel = array_merge(
                $newChildrenLevel,
                range(
                    $pos * $this->matrix->getPow(),
                    ($pos + 1) * $this->matrix->getPow() - 1
                )
            );
        }

        $newResultDepth = [];

        foreach ($newChildrenLevel as $child) {
            $newCoord = new Coord($coord->getDepth() + 1, $child);
            if ($this->matrix->isValidCoord($newCoord)) {
                $newResultDepth[] = $newCoord;
            }
        }

        return $newResultDepth;
    }

}
