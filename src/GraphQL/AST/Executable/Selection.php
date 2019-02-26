<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\GraphQL\AST\Executable;

use Railt\GraphQL\AST\Definition\VariableNode;
use Railt\GraphQL\AST\Proxy\NameProxyTrait;

/**
 * Class Selection
 * @internal This type is generated using a parser, please do not use it inside your application code.
 */
class Selection extends AbstractSelectionSet
{
    use NameProxyTrait;

    /**
     * @var string
     */
    public $type;

    /**
     * @var array|VariableNode[]
     */
    public $variables = [];

    /**
     * Selection constructor.
     * @param string $name
     * @param array $children
     * @param int $offset
     * @throws \Railt\GraphQL\Exception\InternalErrorException
     */
    public function __construct(string $name, array $children = [], int $offset = 0)
    {
        $this->type = \strtolower(\str_replace('Operation', '', $name));

        parent::__construct($name, $children, $offset);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function each($value): bool
    {
        if ($value instanceof VariableNode) {
            $this->variables[] = $value;

            return true;
        }

        return parent::each($value);
    }
}
