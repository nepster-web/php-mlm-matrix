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
     * @var array
     */
    private $matrix;

    /**
     * CellFinder constructor.
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
     *
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
     *
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
     *
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
     * TODO: выходит за пределы матрицы
     *
     * Returns all parent coordinates relative to the current
     *
     * @param Coord $coord
     * @return array
     */
    public function findParentsCoords(Coord $coord): array
    {
        $currentDepth = $coord->getDepth();
        $currentNumber = $coord->getNumber();

        $parents = [];
        while ($currentDepth > 0) {
            $currentDepth--;
            $currentNumber = intval($currentNumber / $this->matrix->getPow());
            $parents[] = [new Coord($currentNumber, $currentDepth)];
        }

        return $parents;
    }

    /**
     * TODO: ????
     *
     * Возвращает координаты всех ячеек сектора текущей ячейки
     *
     * Пример использования:
     * $matrix->getCoordBySector($level, $number, $deep, $all)
     *
     * @param int $level
     * @param int $number
     * @param int $deep
     * @param bool $all
     * @return array
     */
    public function getCoordBySector(int $level, int $number, int $deep = 1, bool $all = false): array
    {
        $number = [$number];
        $possible = [];
        $newResultLevel = [];
        $deep = ($deep < 0) ? 1 : (int)$deep;

        $i = 1;
        while ($deep != 0) {
            $newChildrenLevel = [];
            foreach ($number as $pos) {
                $newChildrenLevel = array_merge($newChildrenLevel,
                    range($pos * $this->matrix->getPow(), ($pos + 1) * $this->matrix->getPow() - 1));
            }

            $newResultLevel = [];

            foreach ($newChildrenLevel as $child) {
                $newResultLevel[] = [
                    'level' => $level + $i,
                    'number' => $child
                ];
            }

            $possible = array_merge($possible, $newResultLevel);
            $number = $newChildrenLevel;
            $deep--;
            $i++;
        }

        if ($all) {
            return $possible;
        }
        return $newResultLevel;
    }

}
