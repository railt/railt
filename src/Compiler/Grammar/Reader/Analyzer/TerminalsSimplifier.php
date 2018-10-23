<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader\Analyzer;

use Railt\Compiler\Grammar\Builder\Buildable;
use Railt\Compiler\Grammar\Builder\Repointable;
use Railt\Compiler\Parser\Rule\Production;
use Railt\Compiler\Parser\Rule\Symbol;

/**
 * Class TerminalsSimplifier
 */
class TerminalsSimplifier extends BaseAnalyzer
{
    /**
     * Source => Target
     *
     * @var array
     */
    private $map = [];

    /**
     * @param iterable|Buildable[]|Symbol[] $rules
     * @return iterable
     */
    public function analyze(iterable $rules): iterable
    {
        foreach ($rules as $id => $rule) {
            $id = $this->map[$rule->getId()] ?? $id;

            yield $id => $this->repoint($rule->move($id));
        }
    }

    /**
     * @param Buildable|Production|Repointable $production
     * @return Buildable
     */
    private function repoint(Buildable $production): Buildable
    {
        if ($production instanceof Production) {
            $children = [];

            foreach ($production->then() as $id) {
                $children[] = $this->map[$id] ?? $id;
            }

            $production->repoint($children);
        }

        return $production;
    }
}
