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

    protected function setUp()
    {
        parent::setUp();

        $this->fixture = new Mustache(
            [
                __DIR__.'/templates/foo/',
                __DIR__.'/templates/bar/',
            ]
        );
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf(AbstractView::class, $this->fixture);
    }

    /**
     * @dataProvider sampleRender
     */
    public function testRender($template, $data, $expected)
    {
        $actual = $this->fixture->render($template, $data);

        $this->assertEquals($expected, $actual);
    }

    public function sampleRender()
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

    public function testRenderNonDefaultExtension()
    {
        $value = uniqid('value');

        $fixture = new Mustache(__DIR__.'/templates/', ['extension' => '.html']);
        $actual  = $fixture->render('baz', ['baz' => $value]);

        $this->assertEquals('Baz is '.$value.PHP_EOL, $actual);
    }

    public function testGetLoader()
    {
        $actual = $this->fixture->getLoader();

        $this->assertInstanceOf(Mustache_Loader::class, $actual);
    }

    public function testGetEngine()
    {
        $actual = $this->fixture->getEngine();

        $this->assertInstanceOf(Mustache_Engine::class, $actual);
    }
}
