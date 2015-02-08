<?php

namespace nepster\matrix;

/**
 * Class Matrix
 * @package nepster\matrix
 */
class Matrix
{

    /**
     * Генерация массива матрицы
     *
     * Простой пример использования:
     * Matrix::generation($view, $levels, $users);
     *
     * Пример использования с callback функцией:
     * Matrix::generation($view, $levels, $users, function($level, $number, $user) {
     *  return $user;
     * });
     *
     * @param int $view
     * @param int $levels
     * @param array $users
     * @param function $callback
     * @return array
     */
    public static function generation($view, $levels, array $users, $callback = null)
    {
        $matrix = [];
        $pointer = 1;
        for ($l = 0; $l < $levels; $l++) {
            for ($n = 0; $n < $pointer; $n++) {
                foreach ($users as $user) {
                    $matrix[$l][$n] = null;
                    if ((isset($user['level']) && $l == $user['level']) && (isset($user['number']) && $user['number'] == $n)) {
                        if ($callback && is_callable($callback)) {
                            $matrix[$l][$n] = call_user_func_array($callback, [$l, $n, $user]);
                        } else {
                            $matrix[$l][$n] = $user;
                        }
                        break;
                    }
                }
            }
            $pointer *= $view;
        }
        return $matrix;
    }

    /**
     * Получить координаты позиции в матрице
     *
     * Пример использования:
     * Matrix::getCoordByPosition($position, $view, $levels);
     *
     * @param int $position
     * @param int $view
     * @param int $levels
     * @return array|false
     */
    public static function getCoordByPosition($position, $view, $levels)
    {
        $result = 0;
        $pointer = 1;
        for ($l = 0; $l < $levels; $l ++) {
            for ($n = 0; $n < $pointer; $n ++) {
                $result ++;
                if ($result == $position) {
                    return [
                        'level' => $l,
                        'number' => $n
                    ];
                }
            }
            $pointer *= $view;
        }
        return false;
    }

    /**
     * Получить позицию в матрице
     *
     * Пример использования:
     * Matrix::getPosition($level, $number, $view)
     *
     * @param int $level
     * @param int $number
     * @param int $view
     * @return int
     */
    public static function getPosition($level, $number, $view)
    {
        if ((int)$level === 0 && (int)$number === 0) {
            return 1;
        } else {
            $result = 0;
            $pointer = 1;
            for ($l = 0; $l <= $level; $l++) {
                for ($n = 0; $n < $pointer; $n ++) {
                    $result++;
                    if ($l == $level && $n == $number) {
                        return $result;
                    }
                }
                $pointer *= $view;
            }
            return false;
        }
    }

    /**
     * Деление матрицы
     *
     * Пример использования:
     * Matrix::getPosition($matrix)
     *
     * @param array $matrix
     * @return array|false
     */
    public static function division(array $matrix)
    {
        if (!isset($matrix[1])) {
            return false;
        }

        $view = count($matrix[1]);
        $levels = count($matrix);

        $matrices = [];
        $pointer = 1;

        for($l = 1; $l < $levels; $l++) {
            $matrices[] = array_chunk($matrix[$l], $pointer);
            $pointer *= $view;
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