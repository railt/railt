<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Compiler;

use Railt\SDL\Parser\Factory;
use Railt\Tests\AbstractTestCase;

/**
 * Class AbstractCompilerTestCase
 */
abstract class AbstractCompilerTestCase extends AbstractTestCase
{
    /**
     * @return string
     */
    protected function getGrammarFile(): string
    {
        return Factory::GRAMMAR_FILE;
    }
}
