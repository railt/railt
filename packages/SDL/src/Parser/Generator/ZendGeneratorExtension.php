<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Parser\Generator;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
use Zend\Code\Generator\ValueGenerator;
use Zend\Code\Generator\Exception\RuntimeException;

/**
 * Class ZendGeneratorExtension
 */
class ZendGeneratorExtension extends AbstractExtension
{
    /**
     * @return array
     * @psalm-suppress InvalidArgument
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('value', [$this, 'value']),
            new TwigFunction('depth', [$this, 'depth']),
            new TwigFunction('rule', [$this, 'rule']),
            new TwigFunction('class', [$this, 'class']),
            new TwigFunction('arguments', [$this, 'arguments']),
        ];
    }

    /**
     * @param mixed $value
     * @param bool $multiline
     * @return string
     * @throws RuntimeException
     */
    public function value($value, bool $multiline = true): string
    {
        $output = $multiline ? ValueGenerator::OUTPUT_MULTIPLE_LINE : ValueGenerator::OUTPUT_SINGLE_LINE;
        $type = ValueGenerator::TYPE_AUTO;

        return (new ValueGenerator($value, $type, $output))->generate();
    }

    /**
     * @param string|object $class
     * @param bool $short
     * @return string
     */
    public function class($class, bool $short = false): string
    {
        if (\is_object($class)) {
            $class = \get_class($class);
        }

        return ! $short ? $class : \basename(\str_replace('\\', \DIRECTORY_SEPARATOR, $class));
    }

    /**
     * @param string $value
     * @param int $depth
     * @return string
     */
    public function depth(string $value, int $depth = 0): string
    {
        $lines = \explode("\n", $value);

        foreach ($lines as $i => $line) {
            $result = \str_repeat(' ', $depth * 4) . $line;

            if (! \trim($result)) {
                $result = '';
            }

            $lines[$i] = $result;
        }

        return \implode("\n", $lines);
    }
}
