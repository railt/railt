<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Exception;

use Phplrt\Contracts\Source\FileInterface;
use Phplrt\Position\Position;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\SDL\Ast\Node;

/**
 * Class GraphQLException
 */
class GraphQLException extends \RuntimeException
{
    /**
     * GraphQLException constructor.
     *
     * @param string $message
     * @param Node|null $node
     * @param \Throwable|null $prev
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    public function __construct(string $message, Node $node = null, \Throwable $prev = null)
    {
        parent::__construct($message, 0, $prev);

        if ($node && $node->loc && $node->loc->source instanceof FileInterface) {
            $source = $node->loc->source;

            $this->file = $source->getPathname();
            $this->line = Position::fromOffset($source, $node->getOffset())->getLine();
        }
    }
}
