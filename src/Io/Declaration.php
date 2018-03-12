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
final class Declaration
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
        $this->file  = $file;
        $this->line  = $line;
        $this->class = $class;
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

    /**
     * @param string $requiredDefinition
     * @return Declaration
     */
    public static function make(string $requiredDefinition): self
    {
        [$file, $line, $class] = ['undefined', 0, null];

        $trace = \array_reverse(\debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        foreach ($trace as $i => $data) {
            $found = \is_subclass_of($data['class'] ?? \stdClass::class, $requiredDefinition);

            if ($found) {
                [$file, $line, $class] = [$data['file'], (int)$data['line'], $trace[$i - 1]['class'] ?? null];
                break;
            }
        }

        return new static($file, $line, $class);
    }
}
