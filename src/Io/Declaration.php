<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Io;

/**
 * Class Declaration
 */
final class Declaration implements DeclarationInterface
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var int
     */
    private $line;

    /**
     * @var null|string
     */
    private $class;

    /**
     * Declaration constructor.
     * @param string $file
     * @param int $line
     * @param null|string $class
     */
    public function __construct(string $file, int $line, ?string $class)
    {
        $this->file = $file;
        $this->line = $line;
        $this->class = $class;
    }

    /**
     * @param string ...$needles
     * @return Declaration
     */
    public static function make(string ...$needles): self
    {
        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        return new static(...self::reduce($needles, $trace));
    }

    /**
     * @param array $needles
     * @param array $trace
     * @return array
     */
    private static function reduce(array $needles, array $trace): array
    {
        [$file, $line, $class] = ['php://input', 0, null];

        foreach ($trace as $i => $item) {
            if (! isset($item['class'])) {
                continue;
            }

            if (self::match($item['class'], ...$needles)) {
                $previous = \max(0, $i - 1);

                return [
                    $item['file'] ?? $file,
                    $item['line'] ?? $line,
                    $trace[$previous]['class'] ?? $class,
                ];
            }
        }

        return [$file, $line, $class];
    }

    /**
     * @param string $class
     * @param string ...$needles
     * @return bool
     */
    private static function match(string $class, string ...$needles): bool
    {
        foreach ($needles as $needle) {
            if (\is_a($needle, $class, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the path and file where this implementation was defined.
     * Required for errors debugging.
     *
     * @return string
     */
    public function getPathname(): string
    {
        return $this->file;
    }

    /**
     * Returns the line where this implementation was defined.
     * Required for errors debugging.
     *
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * Returns the class name where this implementation was defined.
     * Required for errors debugging.
     *
     * @return null|string
     */
    public function getClass(): ?string
    {
        return $this->class;
    }
}
