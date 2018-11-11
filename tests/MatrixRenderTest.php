<?php

use Nepster\Matrix\Coord;
use Nepster\Matrix\Matrix;
use Nepster\Matrix\MatrixRender;

/**
 * Class MatrixRenderTest
 */
class MatrixRenderTest extends \PHPUnit\Framework\TestCase
{

    /** @test */
    public function testRender(): void
    {
        $matrix = new Matrix(2, 2);
        $matrix->addTenant(null, function (): array {
            return ['name' => 'Superman'];
        });

        $render = new MatrixRender($matrix);
        $render->setOptions(['class' => 'matrix']);
        $render->setDepthOptions(['class' => 'depth']);
        $render->setGroupSeparatorOptions(['class' => 'matrix-group-separator']);
        $render->setClearOptions(['style' => 'clear:both']);
        $render->setGroupJoinOptions(['class' => 'matrix-join-group']);
        $render->registerDepthCallback(function (Matrix $matrix, int $d, array $tenants): string {
            return '<div class="depth-counter">Depth ' . (++$d) . '</div>';
        });
        $render->registerCellCallback(function (Matrix $matrix, Coord $coord, $tenant): string {
            if ($tenant === null) {
                return '<div class="cell">free</div>';
            }

            return '<div class="cell">' . $tenant['name'] . '</div>';
        });

        $expected = trim(preg_replace('/\s+/', ' ', $render->show()));
        $actual = trim(preg_replace('/\s+/', ' ', '<div class="matrix"><div class="depth depth-1"><div class="depth-counter">Depth 1</div><div class="cell">Superman</div>
<div style="clear:both"></div></div>
<div class="depth depth-2"><div class="depth-counter">Depth 2</div><div class="cell">free</div>
<div class="cell">free</div>
<div style="clear:both"></div></div> </div>'));

        self::assertEquals($expected, $actual);
    }
}
