<?php

namespace Nepster\Matrix;

/**
 * Class Render
 *
 * @package Nepster\Matrix
 */
class Render
{
    /**
     * @var array
     */
    private $matrix;

    /**
     * @var callable
     */
    private $cellCallback;

    /**
     * @var array
     */
    private $options = ['class' => 'matrix'];

    /**
     * @var array
     */
    private $levelOptions = ['class' => 'level'];

    /**
     * @var array
     */
    private $groupSeparatorOptions = ['class' => 'matrix-group-separator'];

    /**
     * @var array
     */
    private $groupJoinOptions = ['class' => 'matrix-join-group'];

    /**
     * @var array
     */
    private $clearOptions = ['style' => 'clear:both'];

    /**
     * Render constructor.
     * @param Matrix $matrix
     */
    public function __construct(Matrix $matrix)
    {
        $this->matrix = $matrix;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @param array $options
     */
    public function setLevelOptions(array $options): void
    {
        $this->levelOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setGroupSeparatorOptions(array $options): void
    {
        $this->groupSeparatorOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setGroupJoinOptions(array $options): void
    {
        $this->groupSeparatorOptions = $options;
    }

    /**
     * @param array $options
     */
    public function set_clearOptions(array $options): void
    {
        $this->clearOptions = $options;
    }

    /**
     * @param $callback
     */
    public function registerCellCallback($callback): void
    {
        if (is_callable($callback)) {
            $this->cellCallback = $callback;
        }
    }

    /**
     * @param $callback
     */
    public function registerLevelCallback($callback): void
    {
        if (is_callable($callback)) {
            $this->_levelCallback = $callback;
        }
    }

    /**
     * Возвращает html код матрицы
     * @return string
     */
    public function show(): string
    {
        return '<div' . $this->renderTagAttributes($this->options) . '>' . $this->generateMatrixHtml() . '</div>';
    }

    /**
     * Генерирует html код матрицы
     * @return string
     */
    protected function generateMatrixHtml(): string
    {
        $matrixArray = $this->matrix->getArray();

        $result = '';
        $pV = $this->matrix->getView() <= 2 ? 2 : pow($this->matrix->getView(), 2);

        for ($l = 0, $classL = 1; $l < $this->matrix->getLevels(); $l++, $classL++) {

            $LevelClassCounter = ' level-' . $classL;
            $levelOptions = $this->levelOptions;
            if (isset($levelOptions['class'])) {
                $levelOptions['class'] .= $LevelClassCounter;
            } else {
                $levelOptions['class'] = $LevelClassCounter;
            }

            $result .= '<div' . $this->renderTagAttributes($levelOptions) . '>';

            $result .= call_user_func_array($this->_levelCallback, [
                $l,
                $matrixArray[$l],
                $this->matrix
            ]);

            $countL = count($matrixArray[$l]);

            for ($n = 0, $test = 1; $n < $countL; $n++, $test++) {

                if ($l < 3) {
                    /* события до 4 уровня */
                } else {
                    if ($test == 1) {
                        $result .= '<div' . $this->renderTagAttributes($this->groupJoinOptions) . '> ';
                    }
                }

                $result .= call_user_func_array($this->cellCallback, [
                    $l,
                    $n,
                    $matrixArray[$l][$n],
                    $this->matrix
                ]);

                if ($l < 3) {
                    if (($n + 1) != count($matrixArray[$l]) && (($n + 1) % $this->matrix->getView()) == 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->groupSeparatorOptions) . '></div>';
                    }
                } else {
                    if ($l > 1 && ( ($n + 1) % $this->matrix->getView()) == 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->clearOptions) . '></div>';
                    }

                    if ($test == $pV) {

                        $result .= '</div>';
                        $test = 0;

                        if (($n + 1) != count($matrixArray[$l])) {
                            $result .= '<div' . $this->renderTagAttributes($this->groupSeparatorOptions) . '></div>';
                        }
                    }
                }
                $result .= PHP_EOL;
            }

            $result .= '<div' . $this->renderTagAttributes($this->clearOptions) . '></div>';
            $result .= '</div>' . PHP_EOL;
        }

        return $result;
    }

    /**
     * @param array $attributes
     * @return string
     */
    protected function renderTagAttributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $name => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= " $name";
                }
            } else if (is_array($value) && $name === 'data') {
                foreach ($value as $n => $v) {
                    if (is_array($v)) {
                        $html .= " $name-$n='" . json_encode($v, JSON_HEX_APOS) . "'";
                    } else {
                        $html .= " $name-$n=\"" . htmlspecialchars($v) . '"';
                    }
                }
            } else if ($value !== null) {
                $html .= " $name=\"" . htmlspecialchars($value) . '"';
            }
        }

        return $html;
    }
}