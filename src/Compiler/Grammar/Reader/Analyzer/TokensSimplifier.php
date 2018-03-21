<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Reader\Analyzer;

use Railt\Compiler\Parser\Rule\Terminal;

/**
 * Class TokensSimplifier
 */
class TokensSimplifier extends BaseAnalyzer
{
    /**
     * @var array|Terminal[]
     */
    private $kept = [];

    /**
     * @var array|Terminal[]
     */
    private $skip = [];

    /**
     * @param iterable $rules
     * @return iterable
     */
    public function analyze(iterable $rules): iterable
    {
        return $rules;
    }
}
