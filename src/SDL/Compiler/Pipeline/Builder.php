<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler\Pipeline;

use Railt\Reflection\Contracts\Definition;
use Railt\SDL\Compiler\Pipeline;
use Railt\SDL\Compiler\SymbolTable;

/**
 * Class Builder
 */
class Builder extends BaseStage
{
    /**
     * @var Pipeline
     */
    private $pipeline;

    /**
     * Builder constructor.
     * @param Pipeline $pipeline
     */
    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;

        parent::__construct();
    }

    /**
     * @return \Traversable|Definition[]
     */
    public function resolve(): \Traversable
    {
        /** @var SymbolTable $next */
        while ($next = $this->pop()) {
            //$this->pipeline->push();
        }
    }
}
