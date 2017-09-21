<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Reflection;

use Railt\Reflection\Compiler;
use Railt\Reflection\Contracts;
use Railt\Support\Filesystem\File;
use Railt\Tests\AbstractTestCase;

/**
 * Class AbstractReflectionTestCase
 * @package Railt\Tests
 */
abstract class AbstractReflectionTestCase extends AbstractTestCase
{
    /**
     * @param string $body
     * @return Contracts\Document
     * @throws \Railt\Parser\Exceptions\CompilerException
     * @throws \Railt\Parser\Exceptions\UnexpectedTokenException
     * @throws \Railt\Parser\Exceptions\UnrecognizedTokenException
     */
    protected function getDocument(string $body): Contracts\Document
    {
        $readable = File::fromSources($body);

        return (new Compiler())->compile($readable);
    }
}
