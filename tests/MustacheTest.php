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
    private $fixture = null;

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
     * @param mixed $paths
     * @param string $expected
     *
     * @dataProvider sampleInvalidPaths
     */
    public function testInvalidPaths($paths, string $expected): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Path must be a string or an array; '.$expected.' given.');

        new Mustache($paths);
    }

    public function sampleInvalidPaths(): array
    {
        return [
            'null' => [null, 'NULL'],
            'object' => [(object) [], 'object'],
            'false' => [false, 'boolean'],
            'true' => [true, 'boolean'],
            'int' => [rand(), 'integer'],
        ];
    }

    /**
     * @param string $template
     * @param mixed[] $data
     * @param string $expected
     *
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

    public function testStrictCallable(): void
    {
        $value = uniqid('value');

        $data = [
            'foo' => function () use ($value) {
                return $value;
            },
        ];

        $actual = $this->fixture->render('foo', $data);

        self::assertEquals('Foo is '.$value.PHP_EOL, $actual);
    }

    public function testNonStrictCallableFails(): void
    {
        try {
            $this->fixture->render('foo', ['foo' => [$this, 'getCallable']]);
            self::fail('Strict callables is not enabled.');
        } catch (\Throwable $exception) {
            $expected = 'htmlspecialchars() expects parameter 1 to be string, array given';
            self::assertEquals($expected, $exception->getMessage());
        }
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

    /**
     * Sample non-strict callable.
     *
     * @return string
     */
    public function getCallable(): string
    {
        return uniqid();
    }
}
