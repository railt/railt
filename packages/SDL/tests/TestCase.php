<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Tests;

use Railt\SDL\Compiler;
use Railt\SDL\DocumentInterface;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Phplrt\Source\Exception\NotFoundException;
use Phplrt\Source\Exception\NotReadableException;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param string $source
     * @param int $spec
     * @return DocumentInterface
     * @throws NotFoundException
     * @throws NotReadableException
     * @throws \Throwable
     */
    protected function compile(string $source, int $spec = Compiler::SPEC_RAILT): DocumentInterface
    {
        $compiler = new Compiler($spec);

        return $compiler->compile($source);
    }
}
