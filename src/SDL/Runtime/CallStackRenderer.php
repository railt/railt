<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Runtime;

use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Definitions\TypeDefinition;

/**
 * Class CallStackRenderer
 */
class CallStackRenderer
{
    public const TRACE_FILE      = 'file';
    public const TRACE_LINE      = 'line';
    public const TRACE_COLUMN    = 'column';
    public const TRACE_TYPE      = 'type';
    public const TRACE_TYPE_NAME = 'name';
    public const TRACE_TYPE_DEF  = 'definition';

    /**
     * @var array|TypeDefinition[]
     */
    private $stack;

    /**
     * @var array
     */
    private $latest;

    /**
     * CallStackRenderer constructor.
     * @param CallStackInterface $stack
     */
    public function __construct(CallStackInterface $stack)
    {
        $stack = clone $stack;

        $this->latest = $this->definitionToArray($stack->last());
        $this->stack  = $this->stackToArray($stack);
    }

    /**
     * @param Definition $definition
     * @return array
     */
    private function definitionToArray(?Definition $definition): array
    {
        if ($definition === null) {
            return [];
        }

        $file = $definition->getDocument()->getFile();

        return [
            self::TRACE_FILE      => $file->isFile() ? $file->getPathname() : $file->getDefinitionFileName(),
            self::TRACE_LINE      => $definition->getDeclarationLine(),
            self::TRACE_COLUMN    => $definition->getDeclarationColumn(),
            self::TRACE_TYPE      => $definition instanceof TypeDefinition
                ? $definition->getTypeName()
                : $definition->getName(),
            self::TRACE_TYPE_NAME => $definition->getName(),
            self::TRACE_TYPE_DEF  => (string)$definition,
        ];
    }

    /**
     * @param CallStackInterface $stack
     * @return array
     */
    private function stackToArray(CallStackInterface $stack): array
    {
        $result = [];

        while ($item = $stack->last()) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->latest[self::TRACE_COLUMN] ?? 0;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->latest[self::TRACE_FILE] ?? '';
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->latest[self::TRACE_LINE] ?? 0;
    }

    /**
     * @return bool
     */
    public function hasTrace(): bool
    {
        return \count($this->stack) > 0;
    }

    /**
     * @return string
     */
    public function getTraceAsString(): string
    {
        $result = '';

        foreach ($this->getTrace() as $i => $trace) {
            $result .= \vsprintf('#%d %s(%d): In %s %s defined as %s %s', [
                $i,
                $trace[self::TRACE_FILE],
                $trace[self::TRACE_LINE],
                $trace[self::TRACE_TYPE],
                $trace[self::TRACE_TYPE_NAME],
                $trace[self::TRACE_TYPE_DEF],
                \count($this->stack) - 1 > $i ? \PHP_EOL : '',
            ]);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getTrace(): array
    {
        $result = [];

        foreach ($this->stack as $definition) {
            $result[] = $this->definitionToArray($definition);
        }

        return $result;
    }
}
