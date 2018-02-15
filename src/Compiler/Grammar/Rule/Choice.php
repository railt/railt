<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Grammar\Rule;

/**
 * Class Choice
 */
class Choice extends Rule
{
    /**
     * Choice constructor.
     * @param string $name        NodeName
     * @param array $children     [NodeName or ID, NodeName or ID]
     * @param null $nodeId
     */
    public function __construct($name, $children, $nodeId = null)
    {
        /**
         * Example:
         *      (::T_NAME:: | ::T_VARIABLE::) | (::T_VARIABLE:: | ::T_NAME::) ->
         *
         * 0 => new Token(0, 'T_NAME', null, -1, false),
         * 1 => new Token(1, 'T_VARIABLE', null, -1, false),
         * 2 => new Choice(2, [0,1,], null),
         * 3 => new Token(3, 'T_VARIABLE', null, -1, false),
         * 4 => new Token(4, 'T_NAME', null, -1, false),
         * 5 => new Choice(5, [3,4,], null),
         * 'Example' => new Choice('Example', [2,5,], null),
         */
        parent::__construct($name, $children, $nodeId);
    }
}
