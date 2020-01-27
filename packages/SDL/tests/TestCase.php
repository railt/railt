<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests;

use GraphQL\Contracts\TypeSystem\SchemaInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\SimpleCache\InvalidArgumentException;
use Railt\SDL\Compiler;
use Railt\SDL\Spec\SpecificationInterface;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param string $source
     * @param SpecificationInterface|null $spec
     * @return SchemaInterface
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    protected function compile(string $source, SpecificationInterface $spec = null): SchemaInterface
    {
        $compiler = new Compiler($spec);

        return $compiler->compile($source);
    }
}
