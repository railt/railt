<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Json5\Decoder\Ast;

use Phplrt\Ast\RuleInterface;

/**
 * @internal Internal class for json5 abstract syntax tree node representation
 */
class ObjectNode implements NodeInterface
{
    /**
     * @var array
     */
    private $children;

    /**
     * @var int
     */
    private $options;

    /**
     * ObjectNode constructor.
     *
     * @param array $children
     * @param int $options
     */
    public function __construct(array $children = [], int $options = 0)
    {
        $this->children = $children;
        $this->options = $options;
    }

    /**
     * @return array|object
     */
    public function reduce()
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

        if ($this->options & \JSON_OBJECT_AS_ARRAY) {
            return $result;
        }

        return (object)$result;
    }
}
