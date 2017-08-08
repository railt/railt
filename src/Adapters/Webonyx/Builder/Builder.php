<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builder;

use Serafim\Railgun\Adapters\Webonyx\Loader;
use Serafim\Railgun\Adapters\Webonyx\BuilderInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;
use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;

/**
 * Class Builder
 * @package Serafim\Railgun\Adapters\Webonyx
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var Loader
     */
    protected $loader;

    /**
     * @var DefinitionInterface|TypeInterface
     */
    protected $type;

    /**
     * Builder constructor.
     * @param Loader $loader
     * @param DefinitionInterface|TypeInterface $target
     */
    public function __construct(Loader $loader, $target)
    {
        $this->type = $target;
        $this->loader = $loader;
    }

    /**
     * @return Loader
     */
    public function getLoader(): Loader
    {
        return $this->loader;
    }

    /**
     * @return DefinitionInterface|TypeInterface
     */
    public function getTarget()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return mixed
     * @throws \LogicException
     * @throws \Serafim\Railgun\Exceptions\UnexpectedTokenException
     * @throws \Serafim\Railgun\Exceptions\UnrecognizedTokenException
     */
    protected function resolve(string $type)
    {
        return $this->loader->resolve($type);
    }

    /**
     * @param DefinitionInterface|TypeInterface $definition
     * @param string $class
     * @return mixed
     */
    protected function make($definition, string $class)
    {
        return $this->loader->make($definition, $class);
    }
}
