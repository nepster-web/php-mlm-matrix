<?php

namespace Nepster\Matrix;

/**
 * Class MatrixRender
 *
 * @package Nepster\Matrix
 */
class MatrixRender
{
    /**
     * @var Matrix
     */
    private $matrix;

    /**
     * @var callable
     */
    private $cellCallback;

    /**
     * @var callable
     */
    private $depthCallback;

    /**
     * @var array
     */
    private $options = ['class' => 'matrix'];

    /**
     * @var array
     */
    private $depthOptions = ['class' => 'depth'];

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
     * MatrixRender constructor.
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
    public function setDepthOptions(array $options): void
    {
        $this->depthOptions = $options;
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
        $this->groupJoinOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setClearOptions(array $options): void
    {
        $this->clearOptions = $options;
    }

    /**
     * @param callable $callback
     */
    public function registerCellCallback(callable $callback): void
    {
        $this->cellCallback = $callback;
    }

    /**
     * @param callable $callback
     */
    public function registerDepthCallback(callable $callback): void
    {
        $this->depthCallback = $callback;
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
     * Generate matrix html
     * @return string
     */
    protected function generateMatrixHtml(): string
    {
        $matrixArray = $this->matrix->toArray();

        $result = '';
        $pV = $this->matrix->getPow() <= 2 ? 2 : pow($this->matrix->getPow(), 2);

        for ($d = 0, $classD = 1; $d < $this->matrix->getDepth(); ++$d, ++$classD) {

            $depthClassCounter = ' depth-' . $classD;
            $depthOptions = $this->depthOptions;
            if (isset($depthOptions['class'])) {
                $depthOptions['class'] .= $depthClassCounter;
            } else {
                $depthOptions['class'] = $depthClassCounter;
            }

            $result .= '<div' . $this->renderTagAttributes($depthOptions) . '>';

            $result .= call_user_func_array($this->depthCallback, [
                $this->matrix,
                $d,
                $matrixArray[$d],
            ]);

            $countL = count($matrixArray[$d]);

            for ($n = 0, $e = 1; $n < $countL; ++$n, ++$e) {

                if ($d < 3) {
                    /* event before 4 depth */
                } else {
                    if ($e === 1) {
                        $result .= '<div' . $this->renderTagAttributes($this->groupJoinOptions) . '> ';
                    }
                }

                $result .= call_user_func_array($this->cellCallback, [
                    $this->matrix,
                    new Coord($d, $n),
                    $matrixArray[$d][$n],
                ]);

                if ($d < 3) {
                    if (($n + 1) != count($matrixArray[$d]) && (($n + 1) % $this->matrix->getPow()) === 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->groupSeparatorOptions) . '></div>';
                    }
                } else {
                    if ($d > 1 && (($n + 1) % $this->matrix->getPow()) === 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->clearOptions) . '></div>';
                    }

                    if ($e === $pV) {

                        $result .= '</div>';
                        $e = 0;

                        if (($n + 1) !== count($matrixArray[$d])) {
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
            } else {
                if (is_array($value) && $name === 'data') {
                    foreach ($value as $n => $v) {
                        if (is_array($v)) {
                            $html .= " $name-$n='" . json_encode($v, JSON_HEX_APOS) . "'";
                        } else {
                            $html .= " $name-$n=\"" . htmlspecialchars($v) . '"';
                        }
                    }
                } else {
                    if ($value !== null) {
                        $html .= " $name=\"" . htmlspecialchars($value) . '"';
                    }
                }
            }
        }

        return $html;
    }
}
