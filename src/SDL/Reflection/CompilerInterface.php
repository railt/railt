<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection;

use Railt\Io\Readable;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;
use Railt\Reflection\Contracts\Document;
use Railt\SDL\Runtime\CallStack;
use Railt\SDL\Parser\Factory;
use Railt\SDL\Reflection\Validation\Base\ValidatorInterface;

/**
 * Class CompilerInterface
 */
interface CompilerInterface extends Dictionary
{
    /**
     * @param Readable $readable
     * @return Document
     */
    public function compile(Readable $readable): Document;

    /**
     * @param Document $document
     * @return CompilerInterface
     */
    public function add(Document $document): self;

    /**
     * @param \Closure $then
     * @return CompilerInterface
     */
    public function autoload(\Closure $then): self;

    /**
     * @return Factory
     */
    public function getParser(): Factory;

    /**
     * @param string $group
     * @return ValidatorInterface
     */
    public function getValidator(string $group): ValidatorInterface;

    /**
     * @param TypeDefinition $type
     * @return TypeDefinition
     */
    public function normalize(TypeDefinition $type): TypeDefinition;

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary;

    /**
     * @return CallStack
     */
    public function getStack(): CallStack;
}
