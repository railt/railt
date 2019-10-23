<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Contracts\Ast\NodeInterface;
use Phplrt\Source\Exception\NotAccessibleException;
use Railt\Parser\Ast\Generic\RootDirectiveCollection;
use Railt\SDL\Document\MutableDocument;
use Railt\SDL\Exception\TypeErrorException;

/**
 * Class DirectivesRegistrarVisitor
 */
class DirectivesRegistrarVisitor extends RegistrarVisitor
{
    /**
     * @param NodeInterface $node
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    public function leave(NodeInterface $node): void
    {
        if ($node instanceof RootDirectiveCollection) {
            $this->registerDirective($node);
        }
    }

    /**
     * @param RootDirectiveCollection $directives
     * @return void
     * @throws NotAccessibleException
     * @throws TypeErrorException
     * @throws \RuntimeException
     */
    private function registerDirective(RootDirectiveCollection $directives): void
    {
        $this->mutate(static function (MutableDocument $document) use ($directives): void {
            foreach ($directives as $directive) {
                $document->withExecution($directive);
            }
        });
    }
}
