<?php

namespace Bitty\Tests\View;

use Bitty\View\AbstractView;
use Bitty\View\Mustache;
use Mustache_Engine;
use Mustache_Loader;
use PHPUnit\Framework\TestCase;

class MustacheTest extends TestCase
{
    /**
     * @var Mustache
     */
    protected $fixture = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = new Mustache(
            [
                __DIR__.'/templates/foo/',
                __DIR__.'/templates/bar/',
            ]
        );
    }

    public function testInstanceOf(): void
    {
        self::assertInstanceOf(AbstractView::class, $this->fixture);
    }

    /**
     * @dataProvider sampleRender
     */
    public function testRender(string $template, array $data, string $expected): void
    {
        $actual = $this->fixture->render($template, $data);

        self::assertEquals($expected, $actual);
    }

    public function sampleRender(): array
    {
        $value = uniqid('value');

        return [
            'first dir' => [
                'template' => 'foo',
                'data' => ['foo' => $value],
                'expected' => 'Foo is '.$value.PHP_EOL,
            ],
            'second dir' => [
                'template' => 'bar',
                'data' => ['bar' => $value],
                'expected' => 'Bar is '.$value.PHP_EOL,
            ],
            'extension ignored' => [
                'template' => 'foo.mustache',
                'data' => ['foo' => $value],
                'expected' => 'Foo is '.$value.PHP_EOL,
            ],
        ];
    }

    public function testRenderNonDefaultExtension(): void
    {
        $value = uniqid('value');

        $fixture = new Mustache(__DIR__.'/templates/', ['extension' => '.html']);
        $actual  = $fixture->render('baz', ['baz' => $value]);

        self::assertEquals('Baz is '.$value.PHP_EOL, $actual);
    }

    public function testGetLoader(): void
    {
        $actual = $this->fixture->getLoader();

        self::assertInstanceOf(Mustache_Loader::class, $actual);
    }

    public function testGetEngine(): void
    {
        $actual = $this->fixture->getEngine();

        self::assertInstanceOf(Mustache_Engine::class, $actual);
    }
}
