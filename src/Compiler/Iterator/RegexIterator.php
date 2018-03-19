<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Iterator;

/**
 * An iterator which returns a list of regex groups
 */
class RegexIterator implements \IteratorAggregate
{
    public const MODE_ALL                 = 0x00;
    public const MODE_NAMED_GROUPS        = 0x01;
    public const MODE_ALL_GROUPS          = 0x02;
    public const MODE_STRICT_NAMED_GROUPS = 0x03;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var int
     */
    private $mode;

    /**
     * RegexIterator constructor.
     * @param string $pattern
     * @param string $subject
     * @param int $mode
     */
    public function __construct(string $pattern, string $subject, int $mode = self::MODE_ALL)
    {
        $this->mode    = $mode;
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * @param int $mode
     */
    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * @return \Traversable|array[]
     * @throws \RuntimeException
     */
    public function getIterator(): \Traversable
    {
        switch ($this->mode) {
            case self::MODE_NAMED_GROUPS:
                return $this->named(false);

            case self::MODE_ALL_GROUPS:
                return $this->named(true);

            case self::MODE_STRICT_NAMED_GROUPS:
                return $this->named(false, true);

            case self::MODE_ALL:
            default:
                return $this->parse();
        }
    }

    /**
     * @param bool $keepEmptyGroups
     * @param bool $strict
     * @return \Traversable
     * @throws \RuntimeException
     */
    private function named(bool $keepEmptyGroups, bool $strict = false): \Traversable
    {
        foreach ($this->parse() as $result) {
            $last    = '';
            $context = [];

            foreach (\array_reverse($result) as $index => $body) {
                if (! \is_string($index)) {
                    $context[] = $body;
                    continue;
                }

                $last = $index;

                if ($body !== '') {
                    yield $index => \array_reverse($context);
                    continue 2;
                }
            }

            if ($strict) {
                throw $this->emptyLexeme($last);
            }

            if ($keepEmptyGroups) {
                yield \array_reverse($context);
            }
        }
    }

    /**
     * @param string $lastIndex
     * @return \RuntimeException
     */
    private function emptyLexeme(string $lastIndex): \RuntimeException
    {
        $error = 'A lexeme must not match an empty value, which is the case of "%s"';
        return new \RuntimeException(\sprintf($error, $lastIndex));
    }

    /**
     * @return \Traversable
     */
    private function parse(): \Traversable
    {
        $result = new \SplQueue();

        \preg_replace_callback($this->pattern, function (array $matches) use ($result): void {
            $result->push($matches);
        }, $this->subject);

        return $result;
    }

    /**
     * Destroy current body
     */
    public function __destruct()
    {
        unset($this->pattern, $this->subject);
    }
}
