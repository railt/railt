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
use Railt\SDL\Executor\Context;
use Railt\SDL\Executor\Registry;

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
    protected Document $document;

    /**
     * @var Context
     */
    protected Context $context;

    /**
     * TypeRegistrar constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;

        $this->document = $context->getDocument();
        $this->registry = $context->getRegistry();
    }
}
