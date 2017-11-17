<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection;

use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Parser;
use Railt\Compiler\Reflection\Validation\Base\ValidatorInterface;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Filesystem\ReadableInterface;

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
    public function autoload(\Closure $then): self;

    /**
     * @return Parser
     */
    public function getParser(): Parser;

    /**
     * @param string $group
     * @return ValidatorInterface
     */
    public function getValidator(string $group): ValidatorInterface;

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary;

    /**
     * @return CallStack
     */
    public function getStack(): CallStack;
}
