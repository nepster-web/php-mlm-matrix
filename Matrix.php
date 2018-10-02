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
    private $_view = 2;

    /**
     * @var int
     */
    private $_levels = 4;

    /**
     * @var array
     */
    private $_users = [];

    /**
     * @var callback
     */
    private $_callback = null;

    /**
     * @var array
     */
    private $_generation = null;

    /**
     * Init
     *
     * @param $view
     * @param $levels
     */
    public function __construct($view, $levels)
    {
        $this->_view = (int)$view;
        $this->_levels = (int)$levels;
    }

    /**
     * Генерация массива матрицы
     *
     * Простой пример использования:
     * $matrix->generation($view, $levels, $users);
     *
     * Пример использования с callback функцией:
     * $matrix->generation($view, $levels, $users, function($level, $number, $user) {
     *  return $user;
     * });
     *
     * @param array $users
     * @param $callback
     * @return array
     */
    public function generation(array $users = [], $callback = null)
    {
        $matrix = [];
        $pointer = 1;
        for ($l = 0; $l < $this->_levels; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                $matrix[$l][$n] = null;
                foreach ($users as $user) {
                    if ((isset($user['level']) && $l == $user['level']) && (isset($user['number']) && $user['number'] == $n)) {
                        if ($callback && is_callable($callback)) {
                            $matrix[$l][$n] = call_user_func_array($callback, [$l, $n, $user, $this]);
                        } else {
                            $matrix[$l][$n] = $user;
                        }
                        break;
                    }
                }
            }
            $pointer *= $this->_view;
        }
        $this->_generation = $matrix;
        return $matrix;
    }

    /**
     * Вид матрицы
     * @return int
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * Кол-во уровней
     * @return int
     */
    public function getLevels()
    {
        return $this->_levels;
    }

    /**
     * Получить массив матрицы
     * @return array
     */
    public function getArray()
    {
        return $this->_generation;
    }

    /**
     * Получить координаты позиции в матрице
     *
     * Пример использования:
     * $matrix->getCoordByPosition($position);
     *
     * @param int $position
     * @return array|false
     */
    public function getCoordByPosition($position)
    {
        $result = 0;
        $pointer = 1;
        for ($l = 0; $l < $this->_levels; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                $result++;
                if ($result == $position) {
                    return [
                        'level' => $l,
                        'number' => $n
                    ];
                }
            }
            $pointer *= $this->_view;
        }
        return false;
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
    public function getPosition($level, $number)
    {
        if ((int)$level === 0 && (int)$number === 0) {
            return 1;
        } else {
            $result = 0;
            $pointer = 1;
            for ($l = 0; $l <= $level; $l++) {
                for ($n = 0; $n < $pointer; $n++) {
                    $result++;
                    if ($l == $level && $n == $number) {
                        return $result;
                    }
                }
                $pointer *= $this->_view;
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
     * @return array|bool
     */
    public function getCoordFirstFreePosition()
    {
        if (is_array($this->_generation)) {
            foreach ($this->_generation as $l => &$level) {
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
        return false;
    }

    /**
     * Возвращает координаты всех ячеек сектора текущей ячейки
     *
     * Пример использования:
     * $matrix->getCoordBySector($level, $number, $deep, $all)
     *
     * @param $number
     * @param int $deep
     * @param bool $all
     * @return array
     */
    public function getCoordBySector($level, $number, $deep = 1, $all = false)
    {
        $number = [$number];
        $possible = [];
        $new_level_children = [];
        $deep = ($deep < 0) ? 1 : (int)$deep;

        $i = 1;
        while ($deep != 0) {
            $new_level_children = [];
            foreach ($number as $pos) {
                $new_level_children = array_merge($new_level_children, range($pos * $this->_view, ($pos + 1) * $this->_view - 1));
            }

            $new_level_result = [];

            foreach ($new_level_children as $child) {
                $new_level_result[] = [
                    'level' => $level + $i,
                    'number' => $child
                ];
            }

            $possible = array_merge($possible, $new_level_result);
            $number = $new_level_children;
            $deep--;
            $i++;
        }

        if ($all) {
            return $possible;
        }
        return $new_level_result;
    }

    /**
     * Получить все свободные координаты в матрице
     *
     * Пример использования:
     * $this->getFreeCoords()
     *
     * @return array
     */
    public function getFreeCoords()
    {
        $result = [];
        if (is_array($this->_generation)) {
            foreach ($this->_generation as $l => &$level) {
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
    public function getFreePositions()
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
    public function isFilled()
    {
        if (is_array($this->_generation)) {
            foreach ($this->_generation as $l => &$level) {
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
    public function getParentsCoordsByCoord(array $coord)
    {
        $parents = [];
        while ($coord['level'] > 0) {
            $coord['level']--;
            $coord['number'] = intval($coord['number'] / $this->_view);
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
     * @return array|false
     */
    public function division()
    {
        if (!is_array($this->_generation) || !isset($this->_generation[1])) {
            return false;
        }

        $matrices = [];
        $pointer = 1;

        for ($l = 1; $l < $this->_levels; $l++) {
            $matrices[] = array_chunk($this->_generation[$l], $pointer);
            $pointer *= $this->_view;
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