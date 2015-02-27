<?php

namespace nepster\matrix;

/**
 * Class Render
 * @package nepster\matrix
 */
class Render
{
    /**
     * @var array
     */
    private $_matrix;

    /**
     * @var function
     */
    private $_cellCallback;

    /**
     * @var array
     */
    private $_options = ['class' => 'matrix'];

    /**
     * @var array
     */
    private $_levelOptions = ['class' => 'level'];

    /**
     * @var array
     */
    private $_groupSeparatorOptions = ['class' => 'matrix-group-separator'];

    /**
     * @var array
     */
    private $_groupJoinOptions = ['class' => 'matrix-join-group'];

    /**
     * @var array
     */
    private $_clearOptions = ['style' => 'clear:both'];


    /**
     * @param array $matrix
     */
    public function __construct(Matrix $matrix)
    {
        $this->_matrix = $matrix;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->_options = $options;
    }

    /**
     * @param array $options
     */
    public function setLevelOptions(array $options)
    {
        $this->_levelOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setGroupSeparatorOptions(array $options)
    {
        $this->_groupSeparatorOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setGroupJoinOptions(array $options)
    {
        $this->_groupSeparatorOptions = $options;
    }

    /**
     * @param array $options
     */
    public function setClearOptions(array $options)
    {
        $this->_clearOptions = $options;
    }

    /**
     * @param $callback
     */
    public function registerCellCallback($callback)
    {
        if (is_callable($callback)) {
            $this->_cellCallback = $callback;
        }
    }

    /**
     * @param $callback
     */
    public function registerLevelCallback($callback)
    {
        if (is_callable($callback)) {
            $this->_levelCallback = $callback;
        }
    }

    /**
     * Возвращает html код матрицы
     * @return html
     */
    public function show()
    {
        return '<div' . $this->renderTagAttributes($this->_options) . '>' . $this->generateMatrixHtml() . '</div>';
    }

    /**
     * Генерирует html код матрицы
     * @return html
     */
    protected function generateMatrixHtml()
    {
        $matrixArray = $this->_matrix->getArray();

        $result = '';
        $pV = $this->_matrix->getView() <= 2 ? 2 : pow($this->_matrix->getView(), 2);

        for ($l = 0, $classL = 1; $l < $this->_matrix->getLevels(); $l++, $classL++) {

            $LevelClassCounter = ' level-' . $classL;
            $levelOptions = $this->_levelOptions;
            if (isset($levelOptions['class'])) {
                $levelOptions['class'] .= $LevelClassCounter;
            } else {
                $levelOptions['class'] = $LevelClassCounter;
            }

            $result .= '<div' . $this->renderTagAttributes($levelOptions) . '>';

            $result .= call_user_func_array($this->_levelCallback, [
                $l,
                $matrixArray[$l],
                $this->_matrix
            ]);

            $countL = count($matrixArray[$l]);

            for ($n = 0, $test = 1; $n < $countL; $n++, $test++) {

                if ($l < 3) {
                    /* события до 4 уровня */
                } else {
                    if ($test == 1) {
                        $result .= '<div' . $this->renderTagAttributes($this->_groupJoinOptions) . '> ';
                    }
                }

                $result .= call_user_func_array($this->_cellCallback, [
                    $l,
                    $n,
                    $matrixArray[$l][$n],
                    $this->_matrix
                ]);

                if ($l < 3) {
                    if (($n + 1) != count($matrixArray[$l]) && (($n + 1) % $this->_matrix->getView()) == 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->_groupSeparatorOptions) . '></div>';
                    }
                } else {
                    if ($l > 1 && ( ($n + 1) % $this->_matrix->getView()) == 0) {
                        $result .= '<div' . $this->renderTagAttributes($this->_clearOptions) . '></div>';
                    }

                    if ($test == $pV) {

                        $result .= '</div>';
                        $test = 0;

                        if (($n + 1) != count($matrixArray[$l])) {
                            $result .= '<div' . $this->renderTagAttributes($this->_groupSeparatorOptions) . '></div>';
                        }
                    }
                }
                $result .= PHP_EOL;
            }

            $result .= '<div' . $this->renderTagAttributes($this->_clearOptions) . '></div>';
            $result .= '</div>' . PHP_EOL;
        }

        return $result;
    }

    /**
     * @param array $attributes
     * @return string
     */
    protected function renderTagAttributes(array $attributes)
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