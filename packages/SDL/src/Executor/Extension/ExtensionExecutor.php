<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\SDL\Executor\Extension;

use Railt\SDL\Document;
use Phplrt\Visitor\Visitor;

/**
 * Class ExtensionExecutor
 */
abstract class ExtensionExecutor extends Visitor
{
    /**
     * @var Document
     */
    private Document $document;

    /**
     * ExtensionExecutor constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }
}
