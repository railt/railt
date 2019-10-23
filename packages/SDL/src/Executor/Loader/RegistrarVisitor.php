<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Executor\Loader;

use Phplrt\Visitor\Visitor;
use Railt\SDL\Document\MutableDocument;
use Railt\SDL\Document\Document;
use Railt\SDL\Exception\TypeErrorException;
use Phplrt\Source\Exception\NotAccessibleException;

/**
 * Class RegistrarVisitor
 */
abstract class RegistrarVisitor extends Visitor
{
    /**
     * @var string
     */
    private const ERROR_DOCUMENT_MUTATION = 'Сan not register type in immutable document';

    /**
     * @var Document
     */
    protected Document $document;

    /**
     * TypeSystemLoader constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @param \Closure $then
     * @return void
     * @throws TypeErrorException
     * @throws NotAccessibleException
     * @throws \RuntimeException
     */
    protected function mutate(\Closure $then): void
    {
        if ($this->isImmutable()) {
            throw new TypeErrorException(self::ERROR_DOCUMENT_MUTATION);
        }

        $then($this->document);
    }

    /**
     * @return bool
     */
    protected function isImmutable(): bool
    {
        return ! $this->document instanceof MutableDocument;
    }
}
