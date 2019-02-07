<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Ast;

use Railt\Parser\Ast\RuleInterface;

/**
 * Class ObjectNode
 */
class ObjectNode implements NodeInterface
{
    /**
     * @var array
     */
    private $children;

    /**
     * ObjectNode constructor.
     *
     * @param string $name
     * @param array $children
     */
    public function __construct(string $name, array $children = [])
    {
        $this->children = $children;
    }

    /**
     * @return array
     */
    public function reduce(): array
    {
        $result = [];

        /** @var RuleInterface $child */
        foreach ($this->children as $child) {
            /**
             * @var NodeInterface $key
             * @var NodeInterface $value
             */
            [$key, $value] = $child->getChildren();

            $result[$key->reduce()] = $value->reduce();
        }

        return $result;
    }
}
