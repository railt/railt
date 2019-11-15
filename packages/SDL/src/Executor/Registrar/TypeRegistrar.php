<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Registrar;

use Railt\SDL\Document;
use Phplrt\Visitor\Visitor;
use Railt\SDL\Executor\Registry;
use Railt\SDL\Ast\Name\IdentifierNode;

/**
 * Class TypeRegistrar
 */
abstract class TypeRegistrar extends Visitor
{
    /**
     * @var Registry
     */
    protected Registry $registry;

    /**
     * @var Document
     */
    protected Document $dictionary;

    /**
     * Registrar constructor.
     *
     * @param Document $dictionary
     * @param Registry $registry
     */
    public function __construct(Document $dictionary, Registry $registry)
    {
        $this->dictionary = $dictionary;
        $this->registry = $registry;
    }
}
