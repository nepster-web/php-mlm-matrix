<?php

namespace Nepster\Matrix;

/**
 * Class Matrix
 *
 * @package Nepster\Matrix
 *
 */
class Matrix
{
    /**
     * @var int
     */
    private $view = 2;

    /**
     * @var int
     */
    private $levels = 4;

    /**
     * @var array|null
     */
    private $generatedMatrix;

    /**
     * Matrix constructor.
     * @param int $view
     * @param int $levels
     */
    public function __construct(int $view, int $levels)
    {
        $this->view = $view;
        $this->levels = $levels;
    }

    /**
     * Генерация массива матрицы
     *
     * Простой пример использования:
     * $matrix->generation($users);
     *
     * Пример использования с callback функцией:
     * $matrix->generation(array $users, function(int $level, int $number, array $user) {
     *  return $user;
     * });
     *
     * @param array $users
     * @param callable|null $callback
     * @return array
     */
    public function generation(array $users = [], callable $callback = null): array
    {
        $matrix = [];
        $pointer = 1;
        for ($l = 0; $l < $this->levels; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                $matrix[$l][$n] = null;
                foreach ($users as $user) {
                    if ((isset($user['level']) && $l === $user['level']) && (isset($user['number']) && $user['number'] === $n)) {
                        if ($callback) {
                            $matrix[$l][$n] = call_user_func_array($callback, [$l, $n, $user, $this]);
                        } else {
                            $matrix[$l][$n] = $user;
                        }
                        break;
                    }
                }
            }
            $pointer *= $this->view;
        }

        return $this->generatedMatrix = $matrix;
    }

    /**
     * Вид матрицы
     * @return int
     */
    public function getView(): int
    {
        return $this->view;
    }

    /**
     * Кол-во уровней
     * @return int
     */
    public function getLevels(): int
    {
        return $this->levels;
    }

    /**
     * Получить массив матрицы
     * @return array
     */
    public function getArray(): array
    {
        return $this->generatedMatrix;
    }

    /**
     * Получить координаты позиции в матрице
     *
     * Пример использования:
     * $matrix->getCoordByPosition($position);
     *
     * @param int $position
     * @return array|null
     */
    public function getCoordByPosition(int $position): ?array
    {
        $result = 0;
        $pointer = 1;
        for ($l = 0; $l < $this->levels; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                $result++;
                if ($result === $position) {
                    return [
                        'level' => $l,
                        'number' => $n
                    ];
                }
            }
            $pointer *= $this->view;
        }

        return null;
    }

    /**
     * Получить позицию в матрице
     *
     * Пример использования:
     * $matrix->getPosition($level, $number)
     *
     * @param int $level
     * @param int $number
     * @return int
     */
    public function getPosition(int $level, int $number): int
    {
        if ((int)$level === 0 && (int)$number === 0) {
            return 1;
        } else {
            $result = 0;
            $pointer = 1;
            for ($l = 0; $l <= $level; $l++) {
                for ($n = 0; $n < $pointer; $n++) {
                    $result++;
                    if ($l === $level && $n === $number) {
                        return $result;
                    }
                }
                $pointer *= $this->view;
            }
            return false;
        }
    }

    /**
     * Получить координаты первой свободной позиции в матрице
     *
     * Пример использования:
     * $matrix->getCoordFirstFreePosition()
     *
     * @return array|null
     */
    public function getCoordFirstFreePosition(): ?array
    {
        if (is_array($this->generatedMatrix)) {
            foreach ($this->generatedMatrix as $l => &$level) {
                if (is_array($level)) {
                    foreach ($level as $n => &$number) {
                        if (empty($number)) {
                            return [
                                'level' => $l,
                                'number' => $n,
                            ];
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
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
                    range($pos * $this->view, ($pos + 1) * $this->view - 1));
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

    /**
     * Получить все свободные координаты в матрице
     *
     * Пример использования:
     * $this->getFreeCoords()
     *
     * @return array
     */
    public function getFreeCoords(): array
    {
        $result = [];
        if (is_array($this->generatedMatrix)) {
            foreach ($this->generatedMatrix as $l => &$level) {
                if (is_array($level)) {
                    foreach ($level as $n => &$number) {
                        if (empty($number)) {
                            $result[] = [
                                'level' => $l,
                                'number' => $n,
                            ];
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Получить все свободные позиции в матрице
     *
     * Пример использования:
     * $this->getFreePositions()
     *
     * @return array
     */
    public function getFreePositions(): array
    {
        $result = [];
        $freeCoords = $this->getFreeCoords();
        foreach ($freeCoords as $coord) {
            $result[] = $this->getPosition($coord['level'], $coord['number']);
        }
        return $result;
    }

    /**
     * Проверяет заполнена ли матрица
     *
     * Пример использования:
     * $matrix->isFilled()
     *
     * @return bool
     */
    public function isFilled(): bool
    {
        if (is_array($this->generatedMatrix)) {
            foreach ($this->generatedMatrix as $l => &$level) {
                if (is_array($level)) {
                    foreach ($level as $n => &$number) {
                        if (empty($number)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Возвращает все родительские координаты указанной ячейки
     *
     * Пример использования:
     * $matrix->getParentsCoordsByCoord($coord)
     *
     * @param array $coord
     * @return array
     */
    public function getParentsCoordsByCoord(array $coord): array
    {
        $parents = [];
        while ($coord['level'] > 0) {
            $coord['level']--;
            $coord['number'] = intval($coord['number'] / $this->view);
            $parents[] = ['number' => $coord['number'], 'level' => $coord['level']];
        }
        return $parents;
    }

    /**
     * Деление матрицы
     *
     * Пример использования:
     * $matrix->division()
     *
     * @return array
     */
    public function division(): array
    {
        if (!is_array($this->generatedMatrix) || !isset($this->generatedMatrix[1])) {
            return false; // TODO: exeption
        }

        $matrices = [];
        $pointer = 1;

        for ($l = 1; $l < $this->levels; $l++) {
            $matrices[] = array_chunk($this->generatedMatrix[$l], $pointer);
            $pointer *= $this->view;
        }

        $countMatrices = count($matrices);

        $newMatrices = [];

        $x = 0;

        for ($i = 0; $i < $countMatrices; $i++) {
            $count_matrix_lvl = count($matrices[$i]);

            for ($n = 0; $n < $count_matrix_lvl; $n++, $x++) {
                $newMatrices[$x][] = $matrices[$i][$n];
            }
            $x = 0;
        }

        return $newMatrices;
    }

}