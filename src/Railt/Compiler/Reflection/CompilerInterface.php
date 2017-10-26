<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Parser;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Filesystem\ReadableInterface;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Class CompilerInterface
 */
interface CompilerInterface extends Dictionary
{
    /**
     * @param ReadableInterface $readable
     * @return Document
     */
    public function compile(ReadableInterface $readable): Document;

    /**
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function registerAutoloader(\Closure $then): CompilerInterface;

    /**
     * @return Parser
     */
    public function getParser(): Parser;

    /**
     * @return Validator
     */
    public function getValidator(): Validator;
}
