<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Parser\Node;

/**
 * Class NameNode
 *
 * <code>
 *  export type NameNode = {
 *      +kind: 'Name',
 *      +loc?: Location,
 *      +value: string,
 *      ...
 *  };
 * </code>
 *
 * @see https://github.com/graphql/graphql-js/blob/v14.5.7/src/language/ast.js#L184
 */
class NameNode extends Node
{
    /**
     * @var string
     */
    public string $value;

    /**
     * Name constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->value = $name;
    }
}
