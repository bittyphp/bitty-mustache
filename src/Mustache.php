<?php

namespace Bitty\View;

use Bitty\View\AbstractView;
use Mustache_Engine;
use Mustache_Loader;
use Mustache_Loader_CascadingLoader;
use Mustache_Loader_FilesystemLoader;

/**
 * This acts as a very basic wrapper to implement the Mustache templating engine.
 *
 * If more detailed customization is needed, you can access the Mustache engine
 * and loader directly using getEngine() and getLoader(), respectively.
 *
 * @see http://mustache.github.io/
 * @see https://github.com/bobthecow/mustache.php
 */
class Mustache extends AbstractView
{
    /**
    * @var Mustache_Loader
    */
    protected $loader = null;

    /**
     * @var Mustache_Engine
     */
    protected $engine = null;

    /**
     * @param string[]|string $paths
     * @param mixed[] $options
     */
    public function __construct($paths, array $options = [])
    {
        $loaderOptions = [];
        if (isset($options['extension'])) {
            $loaderOptions['extension'] = $options['extension'];
            unset($options['extension']);
        }

        $this->loader = new Mustache_Loader_CascadingLoader();
        foreach ((array) $paths as $path) {
            $this->loader->addLoader(
                new Mustache_Loader_FilesystemLoader($path, $loaderOptions)
            );
        }

        $this->engine = new Mustache_Engine(
            ['loader' => $this->loader] + $options + ['strict_callables' => true]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function render($template, $data = [])
    {
        return $this->engine->loadTemplate($template)->render($data);
    }

    /**
     * Gets the Mustache template loader.
     *
     * This allows for direct manipulation of anything not already defined here.
     *
     * @return Mustache_Loader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Gets the Mustache template engine.
     *
     * This allows for direct manipulation of anything not already defined here.
     *
     * @return Mustache_Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }
}
