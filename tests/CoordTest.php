<?php

use Nepster\Matrix\Coord;

/**
 * Class CoordTest
 */
class CoordTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function testItReturnsCurrentDepth(): void
    {
        $coord = new Coord(1, 2);

        self::assertEquals($coord->getDepth() ,1);
    }

    /** @test */
    public function testItReturnsCurrentNumber(): void
    {
        $coord = new Coord(1, 2);

        self::assertEquals($coord->getNumber() ,2);
    }
}
