<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Dumper\Resolver;

/**
 * Class GeneratorResolver
 */
class GeneratorResolver extends ObjectResolver
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function match($value): bool
    {
        if ($value instanceof \IteratorAggregate) {
            return $value->getIterator() instanceof \Generator;
        }

        return $value instanceof \Generator;
    }

    /**
     * @param \Traversable|\Generator $value
     * @return string
     */
    public function value($value): string
    {
        /** @var \Generator $generator */
        $generator = $value instanceof \IteratorAggregate ? $value->getIterator() : $value;

        $suffix = '<' . $this->getReturn($generator) . '>';

        return parent::value($value) . $suffix;
    }

    /**
     * @param \Generator $generator
     * @return string
     */
    private function getReturn(\Generator $generator): string
    {
        if ($generator->valid()) {
            return $this->dumper->dump($generator->current()) . ' … ';
        }

        return ' … ' . $this->dumper->dump($generator->getReturn());
    }
}
