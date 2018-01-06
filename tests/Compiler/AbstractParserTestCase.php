<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\Tests\AbstractTestCase;

/**
 * Class AbstractParserTest
 */
abstract class AbstractParserTestCase extends AbstractTestCase
{
    /**
     * @return string
     */
    protected function getGrammarFile(): string
    {
        return __DIR__ . '/.resources/2017-12-26-grammar.pp';
    }
}
