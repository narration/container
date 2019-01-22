<?php

declare(strict_types=1);

/**
 * This file is part of Narration Framework.
 *
 * (c) Nuno Maduro <enunomaduro@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Narration\Container;

use function is_callable;
use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var mixed[]
     */
    private $definitions;

    /**
     * @var \League\Container\Container
     */
    private $baseContainer;

    /**
     * Container constructor.
     *
     * @param array $definitions
     */
    public function __construct(array $definitions = [])
    {
        $this->definitions = $definitions;

        $this->baseContainer = (new \League\Container\Container())->delegate(new \League\Container\ReflectionContainer);

        foreach ($definitions as $id => $concrete) {
            $this->baseContainer->add($id, $concrete);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (array_key_exists($id, $this->definitions) && is_callable($this->definitions[$id])) {
            $this->baseContainer->add($id, $this->definitions[$id]($this));
        }

        return $this->baseContainer->get($id);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return $this->baseContainer->has($id);
    }

    /**
     * @param  array $injectors
     *
     * @return \Psr\Container\ContainerInterface
     */
    public static function makeWithInjectors(array $injectors): ContainerInterface
    {
        $definitions = [];
        foreach ($injectors as $injector) {
            $definitions = array_merge(new $injector());
        }

        return new self($definitions);
    }
}
