<?php

declare(strict_types=1);

namespace Tests\Unit\Renderer;

use BombenProdukt\Xit\Parser\DocumentParser;
use BombenProdukt\Xit\Renderer\StyleRenderer;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('should render the document', function (): void {
    $parser = new DocumentParser();
    $parsed = $parser->parse(xit());

    $renderer = new StyleRenderer();

    assertMatchesSnapshot($renderer->render($parsed));
});
