<?php

namespace Nepster\Matrix;

/**
 * Class Coord
 *
 * @package Nepster\Matrix
 */
class Coord
{
    /**
     * @var int
     */
    private $depth;

    /**
     * @var int
     */
    private $number;

    /**
     * Coord constructor.
     * @param int $depth
     * @param int $number
     */
    public function __construct(int $depth, int $number)
    {
        $this->depth = $depth;
        $this->number = $number;
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
    public function getNumber(): int
    {
        return $this->number;
    }
}
