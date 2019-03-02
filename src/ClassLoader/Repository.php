<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\ClassLoader;

use Railt\SDL\Contracts\Definitions\TypeDefinition;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Schema\CompilerInterface;
use Railt\SDL\Schema\Configuration;

/**
 * Class Repository
 */
class Repository
{
    /**
     * @var string
     */
    private const DIRECTIVE_NAME = 'use';

    /**
     * @var string
     */
    private const DIRECTIVE_ARGUMENT_CLASS = 'class';

    /**
     * @var string
     */
    private const DIRECTIVE_ARGUMENT_ALIAS = 'as';

    /**
     * @var string
     */
    private const DIRECTIVE_ARGUMENT_SCOPE = 'scope';

    /**
     * @var string
     */
    private const DIRECTIVE_ARGUMENT_SCOPE_GLOBAL = 'GLOBAL';

    /**
     * @var Dictionary
     */
    private $dictionary;

    /**
     * @var array
     */
    private $documents = [];

    /**
     * @var array|string[]
     */
    private $globals = [];

    /**
     * Repository constructor.
     *
     * @param CompilerInterface&Configuration $compiler
     */
    public function __construct(CompilerInterface $compiler)
    {
        $this->dictionary = $compiler->getDictionary();
    }

    /**
     * @param TypeDefinition $definition
     * @return bool
     */
    private function isLoaded(TypeDefinition $definition): bool
    {
        $id = $definition->getDocument()->getName();

        if (! \in_array($id, $this->documents, true)) {
            $this->documents[] = $id;

            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    private function boot(): void
    {
        foreach ($this->dictionary->all() as $definition) {
            if (! $this->isLoaded($definition)) {
                $this->readDocumentAliases($definition->getDocument());
            }
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @return bool
     */
    private function isGlobal(DirectiveInvocation $directive): bool
    {
        $global = self::DIRECTIVE_ARGUMENT_SCOPE_GLOBAL;

        return $directive->getPassedArgument(self::DIRECTIVE_ARGUMENT_SCOPE) === $global;
    }

    /**
     * @param Document $document
     * @return array
     */
    private function readDocumentAliases(Document $document): array
    {
        $result = $this->globals;

        foreach ($document->getDirectives(self::DIRECTIVE_NAME) as $directive) {
            [$alias, $class] = $this->unpack($directive);

            if ($this->isGlobal($directive)) {
                $this->globals[$alias] = $class;
            }

            $result[$alias] = $class;
        }

        return $result;
    }

    /**
     * @param DirectiveInvocation $directive
     * @return array
     */
    private function unpack(DirectiveInvocation $directive): array
    {
        [$class, $alias] = [
            $directive->getPassedArgument(self::DIRECTIVE_ARGUMENT_CLASS),
            $directive->getPassedArgument(self::DIRECTIVE_ARGUMENT_ALIAS),
        ];

        return [$alias ?? $this->unpackSuffix($class), $class];
    }

    /**
     * @param string $class
     * @return string
     */
    private function unpackSuffix(string $class): string
    {
        $parts = \explode('\\', $class);

        return \end($parts);
    }

    /**
     * @param Document $document
     * @return array
     */
    public function getAliases(Document $document): array
    {
        $this->boot();

        return $this->readDocumentAliases($document);
    }
}
