<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Position;

use Railt\Component\Io\File;
use Railt\Component\Io\Readable;

/**
 * Class Highlight
 */
class Highlight implements HighlightInterface
{
    /**
     * @var int
     */
    public const DEFAULT_SIZE = 2;

    /**
     * @var string[]|array
     */
    private $code;

    /**
     * @var int
     */
    private $line;

    /**
     * Highlight constructor.
     *
     * @param Readable $sources
     * @param int $line
     * @param int $size
     */
    public function __construct(Readable $sources, int $line = 1, int $size = self::DEFAULT_SIZE)
    {
        $this->line = $line;
        $this->code = $this->read($sources, $line, $size);
    }

    /**
     * @param Readable $sources
     * @param int $line
     * @param int $size
     * @return array
     */
    private function read(Readable $sources, int $line, int $size): array
    {
        $result = [];
        $stream = $sources->getStreamContents();

        for ($current = 1; ! \feof($stream); ++$current) {
            $code = \fgets($stream);

            if ($current >= $line - $size) {
                $result[$current] = \str_replace(["\r", "\n"], '', $code);
            }

            if ($current >= $line + $size) {
                break;
            }
        }

        \fclose($stream);

        return $result;
    }

    /**
     * @param string $sources
     * @param int $offset
     * @param int $size
     * @return Highlight
     */
    public static function fromOffset(string $sources, int $offset = 0, int $size = self::DEFAULT_SIZE): self
    {
        $file = File::fromSources($sources);

        return new static($file, $file->getPosition($offset)->getLine(), $size);
    }

    /**
     * @param int $from
     * @param int|null $length
     * @return string
     */
    public function render(int $from = 1, int $length = null): string
    {
        return $this->renderWithMessage('', $from, $length);
    }

    /**
     * @param string $message
     * @param int $from
     * @param int|null $length
     * @return string
     */
    public function renderWithMessage(string $message, int $from = 1, int $length = null): string
    {
        $result = $this->prepareRenderResult($message, $from, $length);

        return \implode(\PHP_EOL, $result);
    }

    /**
     * @param string $message
     * @param int $from
     * @param int|null $length
     * @return array
     */
    private function prepareRenderResult(string $message, int $from, ?int $length): array
    {
        $from = \max(0, $from - 1);
        $result = [];

        foreach ($this->code as $index => $code) {
            $result[] = $this->renderLine($code, $index);

            if ($index === $this->line) {
                $length = $length ?? \max(1, $from - \mb_strlen($code));

                $result[] = $this->renderErrorUnderscore($from, $length, $message);
            }
        }

        return $result;
    }

    /**
     * @param string $body
     * @param int|null $line
     * @return string
     */
    private function renderLine(string $body, int $line = null): string
    {
        if ($line === null) {
            return \sprintf('     |%s', $body);
        }

        return \sprintf('%4d | %s', $line, $body);
    }

    /**
     * @param int $from
     * @param int $length
     * @param string $message
     * @return string
     */
    private function renderErrorUnderscore(int $from, int $length, string $message): string
    {
        $underscore = \str_repeat(' ', $from) . \str_repeat('^', $length);

        if ($message) {
            $underscore .= ' ' . $message . ' ';
        }

        return $this->highlight($this->renderLine($underscore));
    }

    /**
     * @param string $text
     * @return string
     */
    private function highlight(string $text): string
    {
        if (\PHP_SAPI === 'cli') {
            return \sprintf("\033[1;37m\033[41m%s\033[0;39m", $text);
        }

        return $text;
    }
}
