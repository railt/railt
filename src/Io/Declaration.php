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
        [$file, $line, $class] = ['undefined', 0, null];

        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        foreach ($trace as $i => $current) {
            if (isset($current['class'])) {
                $class = $current['class'];

                foreach ($needles as $needle) {
                    if ($class === $needle || \is_a($class, $needle, true)) {
                        [$file, $line, $class] = self::extractInfoFromTrace($current);

                        break 2;
                    }
                }
            }
        }

        return new static($file, $line, $class);
    }

    /**
     * @param array $trace
     * @return array
     */
    private static function extractInfoFromTrace(array $trace): array
    {
        return [
            $trace['file'],
            $trace['line'],
            $trace['class'] ?? null,
        ];
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
