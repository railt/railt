<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx;

use Serafim\Railgun\Reflection\Abstraction\DefinitionInterface;
use Serafim\Railgun\Reflection\Abstraction\Type\TypeInterface;

/**
 * Interface BuilderInterface
 * @package Serafim\Railgun\Adapters\Webonyx
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param Loader $loader
     * @param DefinitionInterface|TypeInterface $target
     */
    public function __construct(Loader $loader, $target);

    /**
     * @return Loader
     */
    public function getLoader(): Loader;

    /**
     * @return DefinitionInterface|TypeInterface
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function build();
}
