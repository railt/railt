<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader\Analyzer;

use Railt\Compiler\Parser\Rule\Rule;
use Railt\Compiler\Parser\Rule\Terminal;

/**
 * Class TokensSimplifier
 */
class TokensSimplifier extends BaseAnalyzer
{
    /**
     * @var array|Terminal[]
     */
    private $tokens = [];

    /**
     * @var array
     */
    private $replacements = [];

    /**
     * @param iterable|Rule[] $rules
     * @return iterable
     */
    public function analyze(iterable $rules): iterable
    {
        //
        // Collect all terminals (tokens) and its duplications
        //
        foreach ($rules as $rule) {
            if ($rule instanceof Terminal) {
                $this->save($rule);
            }
        }

        //
        // Relink all token duplications to new identifier and remove duplicated tokens.
        //
        $rules = $this->applyReplacements($rules);

        //
        // TODO Normalize identifiers and creating an integral incremental composition
        //
        return $rules;
    }

    /**
     * @param iterable $rules
     * @return array|Rule[]
     */
    private function applyReplacements(iterable $rules): array
    {
        $mapped = [];

        /** @var Rule $rule */
        foreach ($rules as $id => $rule) {
            if (! \array_key_exists($id, $this->replacements)) {
                $rule->setChildrent($this->map($rule->getChildren(), $this->replacements));

                $mapped[$id] = $rule;
            }
        }

        return $mapped;
    }

    /**
     * @param array|int|mixed $children
     * @param array $replacements
     * @return array|int|mixed
     */
    private function map($children, array $replacements)
    {
        switch (true) {
            case \is_int($children):
                return $replacements[$children] ?? $children;
            case \is_array($children):
                $result = [];
                foreach ((array)$children as $id) {
                    $result[] = $this->map($id, $replacements);
                }
                return $result;
        }

        return $children;
    }

    /**
     * @param Terminal $needle
     */
    private function save(Terminal $needle): void
    {
        foreach ($this->tokens as $haystack) {
            $isSameName = $haystack->getTokenName() === $needle->getTokenName();

            if ($isSameName && $haystack->isKept() === $needle->isKept()) {
                $this->replacements[$needle->getName()] = $haystack->getName();
                return;
            }
        }

        $this->tokens[] = $needle;
    }
}
