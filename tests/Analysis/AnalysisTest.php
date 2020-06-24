<?php

declare(strict_types=1);

namespace Andali\Companies\Analysis;

use GrahamCampbell\Analyzer\AnalysisTrait;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class AnalysisTest extends TestCase
{
    use AnalysisTrait;

    public function getPaths(): array
    {
        return [
            realpath(__DIR__.'/../../config'),
            realpath(__DIR__.'/../../src'),
            realpath(__DIR__),
        ];
    }
}
