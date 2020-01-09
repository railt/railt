<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\CodeGenerator\Value;

use Railt\TypeSystem\Value\StringValue;

/**
 * @property-read StringValue $value
 */
class StringValueGenerator extends ValueGenerator
{
    /**
     * @var string
     */
    private const PATTERN_NEW_LINE = '/\\\\n/um';

    /**
     * @return string
     */
    public function toString(): string
    {
        $inline = \trim($this->getValue());

        if ($this->isMultiline() === false) {
            return $this->format($inline, false);
        }

        $lines = $this->formatLines(\preg_split(self::PATTERN_NEW_LINE, $inline));

        return $this->format("\n" . \trim(\implode("\n", $lines), "\n") . "\n", true);
    }

    /**
     * @return string
     */
    private function getValue(): string
    {
        $value = $this->value->toPHPValue();

        $options = $this->isUnicode() ? \JSON_UNESCAPED_UNICODE : 0;

        $inline = \json_encode($value, \JSON_THROW_ON_ERROR | $options);

        return \substr($inline, 1, -1);
    }

    /**
     * @param string $value
     * @param bool $multiline
     * @return string
     */
    private function format(string $value, bool $multiline): string
    {
        return \sprintf($multiline ? '"""%s"""' : '"%s"', $value);
    }

    /**
     * @param array $lines
     * @return array
     */
    protected function formatLines(array $lines): array
    {
        $first = true;

        return \array_map(function (string $line) use (&$first): string {
            $result = $this->line($line, $first ? 0 : $this->depth());

            $first = false;

            return $result;
        }, $lines);
    }
}

