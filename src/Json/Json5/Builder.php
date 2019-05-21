<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5;

use Phplrt\Parser\Builder as BaseAstBuilder;
use Phplrt\Parser\GrammarInterface;
use Railt\Json\Json5\Ast\NodeInterface;

/**
 * Class Builder
 */
class Builder extends BaseAstBuilder
{
    /**
     * @var int
     */
    private $options;

    /**
     * Builder constructor.
     *
     * @param array $trace
     * @param GrammarInterface $grammar
     * @param int $options
     */
    public function __construct(array $trace, GrammarInterface $grammar, int $options)
    {
        $this->options = $options;

        parent::__construct($trace, $grammar);
    }

    /**
     * @param string $name
     * @param array $children
     * @param int $offset
     * @return mixed
     */
    protected function getRule(string $name, array $children, int $offset)
    {
        $class = $this->getRuleClass($name);

        if (\is_subclass_of($class, NodeInterface::class)) {
            return new $class($children, $this->options);
        }

        return new $class($name, $children, $offset);
    }
}
