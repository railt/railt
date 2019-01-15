<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\ClassLoader;

use Illuminate\Support\Str;
use Railt\ClassLoader\Exception\InvalidActionException;
use Railt\ClassLoader\Exception\UnknownClassException;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;

/**
 * Class DirectiveClassLoader
 */
class DirectiveClassLoader implements ClassLoaderInterface
{
    /**
     * @var string
     */
    private const ACTION_DELIMITER = '@';

    /**
     * A list of global directives.
     *
     * @var array
     */
    private $directives = [];

    /**
     * @param Document $document
     * @param string $action
     * @param int $line
     * @return array
     * @throws UnknownClassException
     * @throws InvalidActionException
     */
    public function action(Document $document, string $action, int $line = 0): array
    {
        $parts = \explode(self::ACTION_DELIMITER, $action);

        if (\count($parts) === 1) {
            $parts[] = '__invoke';
        }

        if (\count($parts) !== 2) {
            $error = 'An action should contain an urn like "Class%saction", but "%s" given';

            $exception = new InvalidActionException(\sprintf($error, self::ACTION_DELIMITER, $action));
            $exception->throwsIn($document->getFile(), $line, 0);

            throw new InvalidActionException(\sprintf($error, self::ACTION_DELIMITER, $action));
        }

        [$class, $method] = $parts;

        $class = $this->load($document, $class);

        if (! \method_exists($class, $method) && ! \method_exists($class, '__call')) {
            $error = 'There is no method "%s" in the class "%s" defined by "%s" action';

            $exception = new InvalidActionException(\sprintf($error, $method, $class, $action));
            $exception->throwsIn($document->getFile(), $line, 0);

            throw $exception;
        }

        return [$class, $method];
    }

    /**
     * @param Document $document
     * @param string $class
     * @param int $line
     * @return string
     * @throws UnknownClassException
     */
    public function load(Document $document, string $class, int $line = 0): string
    {
        if (\class_exists($class)) {
            return $class;
        }

        $result = $this->loadFromDirective($document, $class);

        if ($result !== null) {
            return $result;
        }

        $error = 'Class "%s" not found in action argument';

        $exception = new UnknownClassException(\sprintf($error, $class));
        $exception->throwsIn($document->getFile(), $line, 0);

        throw $exception;
    }

    /**
     * @param Document $document
     * @param string $needle
     * @return null|string
     * @throws \Railt\ClassLoader\Exception\UnknownClassException
     */
    private function loadFromDirective(Document $document, string $needle): ?string
    {
        foreach ($this->getDirectives($document) as $directive) {
            [$class, $alias] = $this->getDirectiveArguments($directive);

            switch (true) {
                case $this->compareAlias($needle, $alias):
                    return $class;

                case $this->compareNamespace($needle, $class):
                    return $class;
            }
        }

        return null;
    }

    /**
     * @param Document $document
     * @return iterable|DirectiveInvocation[]
     */
    private function getDirectives(Document $document): iterable
    {
        foreach ($this->directives as $directive) {
            yield $directive;
        }

        foreach ($document->getDirectives('use') as $directive) {
            if ($directive->getPassedArgument('scope') === 'GLOBAL') {
                $this->directives[] = $directive;
            }

            yield $directive;
        }
    }

    /**
     * @param DirectiveInvocation $directive
     * @return array
     */
    private function getDirectiveArguments(DirectiveInvocation $directive): array
    {
        return [
            $directive->getPassedArgument('class'),
            $directive->getPassedArgument('as'),
        ];
    }

    /**
     * @param string $class
     * @param string $alias
     * @return bool
     */
    private function compareAlias(string $class, ?string $alias): bool
    {
        return $alias === $class;
    }

    /**
     * @param string $class
     * @param string $needle
     * @return bool
     * @throws \Railt\ClassLoader\Exception\UnknownClassException
     */
    private function compareNamespace(string $needle, string $class): bool
    {
        $isMatched = Str::endsWith($class, '\\' . $needle);

        if ($isMatched && ! \class_exists($class)) {
            $error = 'The match to the class "%s" was found, but the class itself is not defined';
            throw new UnknownClassException(\sprintf($error, $class));
        }

        return $isMatched;
    }
}
