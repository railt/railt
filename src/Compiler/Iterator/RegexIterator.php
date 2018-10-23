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
    public const PREG_PARSING_ERROR          = 'The error occurs while compiling PCRE';
    public const PREG_INTERNAL_ERROR         = 'There was an internal PCRE error';
    public const PREG_BACKTRACK_LIMIT_ERROR  = 'Backtrack limit was exhausted';
    public const PREG_RECURSION_LIMIT_ERROR  = 'Recursion limit was exhausted';
    public const PREG_BAD_UTF8_ERROR         = 'The offset didn\'t correspond to the begin of a valid UTF-8 code point';
    public const PREG_BAD_UTF8_OFFSET_ERROR  = 'Malformed UTF-8 data';

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $subject;

    /**
     * RegexIterator constructor.
     * @param string $pattern
     * @param string $subject
     */
    public function __construct(string $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    /**
     * @return \Traversable|array[]
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getIterator(): \Traversable
    {
        $result = new \SplQueue();

        $status = @\preg_replace_callback($this->pattern, function (array $matches) use ($result): void {
            $result->push($matches);
        }, $this->subject);

        $this->validate($status);

        return $result;
    }

    /**
     * @param $status
     * @return void
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function validate($status): void
    {
        $code = \preg_last_error();

        if ($code !== \PREG_NO_ERROR) {
            throw new \RuntimeException($this->getErrorMessage($code), $code);
        }

        if ($status === null) {
            $parts = \explode(':', \error_get_last()['message'] ?? '');
            $error = \sprintf('%s, %s', self::PREG_PARSING_ERROR, \trim(\end($parts)));
            throw new \InvalidArgumentException($error);
        }
    }

    /**
     * @param int $code
     * @return string
     */
    private function getErrorMessage(int $code): string
    {
        switch ($code) {
            case \PREG_INTERNAL_ERROR:
                return self::PREG_INTERNAL_ERROR;
            case \PREG_BACKTRACK_LIMIT_ERROR:
                return self::PREG_BACKTRACK_LIMIT_ERROR;
            case \PREG_RECURSION_LIMIT_ERROR:
                return self::PREG_RECURSION_LIMIT_ERROR;
            case \PREG_BAD_UTF8_ERROR:
                return self::PREG_BAD_UTF8_ERROR;
            case \PREG_BAD_UTF8_OFFSET_ERROR:
                return self::PREG_BAD_UTF8_OFFSET_ERROR;
        }
        return 'Unexpected PCRE error (Code ' . $code . ')';
    }

    /**
     * Destroy current body
     */
    public function __destruct()
    {
        unset($this->pattern, $this->subject);
    }
}
