<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Kernel;

use Illuminate\Support\Str;
use Railt\Foundation\Kernel\Contracts\ClassLoader;
use Railt\Foundation\Kernel\Exceptions\InvalidActionException;
use Railt\Foundation\Kernel\Exceptions\UnknownClassException;
use Railt\SDL\Contracts\Document;
use Railt\SDL\Contracts\Invocations\DirectiveInvocation;

/**
 * Class DirectiveLoader
 */
class DirectiveLoader implements ClassLoader
{
    /**
     * A list of global directives.
     *
     * @var array
     */
    private $directives = [];

    private const ACTION_DELIMITER = '@';

    /**
     * @param Document $document
     * @param string $action
     * @return array
     * @throws \Railt\Foundation\Kernel\Exceptions\UnknownClassException
     * @throws \Railt\Foundation\Kernel\Exceptions\InvalidActionException
     */
    public function action(Document $document, string $action): array
    {
        [$class, $method] = \tap(\explode(self::ACTION_DELIMITER, $action), function (array $parts) use ($action): void {
            if (\count($parts) !== 2) {
                $error = 'The action route argument must contain an urn in the format "Class%saction", but "%s" given';
                throw new InvalidActionException(\sprintf($error, self::ACTION_DELIMITER, $action));
            }
        });

        $class = $this->load($document, $class);

        if (! \method_exists($class, $method) && ! \method_exists($class, '__call')) {
            $error = 'In the action "%s" in the indicated class "%s" there is no method "%s"';
            throw new InvalidActionException(\sprintf($error, $action, $class, $method));
        }

        return [$class, $method];
    }

    /**
     * @param Document $document
     * @param string $class
     * @return string
     * @throws \Railt\Foundation\Kernel\Exceptions\UnknownClassException
     */
    public function load(Document $document, string $class): string
    {
        if (\class_exists($class)) {
            return $class;
        }

        $result = $this->loadFromDirective($document, $class);

        if ($result !== null) {
            return $result;
        }

        $error = 'Class "%s" is not found in the definition of route action argument';
        throw new UnknownClassException(\sprintf($error, $class));
    }

    /**
     * @param Document $document
     * @param string $needle
     * @return null|string
     * @throws \Railt\Foundation\Kernel\Exceptions\UnknownClassException
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
     * @throws \Railt\Foundation\Kernel\Exceptions\UnknownClassException
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
