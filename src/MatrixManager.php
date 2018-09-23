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
        for ($l = 0; $l < $this->depth; $l++) {
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
            $pointer *= $this->pow;
        }

        return $this->generatedMatrix = $matrix;
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
        $matrices = [];
        $pointer = 1;

        for ($l = 1; $l < $this->depth; $l++) {
            $matrices[] = array_chunk($this->generatedMatrix[$l], $pointer);
            $pointer *= $this->pow;
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
