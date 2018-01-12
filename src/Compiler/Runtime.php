<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler;

use Railt\Compiler\Runtime\Analyzer;
use Railt\Compiler\Runtime\Grammar;
use Railt\Io\Readable;

/**
 * Class Runtime
 */
class Runtime extends Parser
{
    /**
     * Runtime constructor.
     * @param Readable $grammar
     * @throws \LogicException
     */
    public function __construct(Readable $grammar)
    {
        $parser                     = new Grammar($grammar);
        [$tokens, $rules, $pragmas] = [$parser->getTokens(), $parser->getRules(), $parser->getPragmas()];

        $analyzer = new Analyzer($tokens);
        $rules    = $analyzer->analyzeRules($rules);

        parent::__construct($tokens, $rules, $pragmas);
    }
}
