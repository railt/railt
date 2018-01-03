<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser;

use Railt\Parser\Io\Readable;
use Railt\Parser\Runtime\Grammar;
use Railt\Parser\Runtime\Analyzer;

/**
 * Class Runtime
 */
class Runtime extends Parser
{
    /**
     * Runtime constructor.
     * @param Readable $grammar
     */
    public function __construct(Readable $grammar)
    {
        $parser = new Grammar($grammar);
        [$tokens, $rules, $pragmas] = [$parser->getTokens(), $parser->getRules(), $parser->getPragmas()];

        $analyzer = new Analyzer($tokens);
        $rules = $analyzer->analyzeRules($rules);

        parent::__construct($tokens, $rules, $pragmas);
    }
}
