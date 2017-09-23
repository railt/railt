<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Directives;

use Railt\Reflection\Base\BaseArgument;
use Railt\Reflection\Base\BaseDirective;
use Railt\Reflection\Contracts\Behavior\Inputable;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\ArgumentType;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Contracts\Types\TypeInterface;
use Railt\Reflection\Standard\StandardType;

/**
 * Class Deprecation
 *
 * @see https://github.com/graphql/graphql-js/pull/384
 */
class Deprecation extends BaseDirective implements StandardType
{
    /**
     * Deprecation directive name
     */
    private const TYPE_NAME = 'deprecated';

    /**
     * Deprecation constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document          = $document;
        $this->name              = self::TYPE_NAME;
        $this->deprecationReason = self::RFC_IMPL_DESCRIPTION;


        $argument = $this->createReasonArgument();
        $this->arguments[$argument->getName()] = $argument;
    }

    /**
     * @return ArgumentType
     */
    private function createReasonArgument(): ArgumentType
    {
        return new class($this->getDocument(), $this) extends BaseArgument
        {
            private const ARGUMENT_NAME = 'reason';
            private const ARGUMENT_TYPE = 'String';
            private const ARGUMENT_DEFAULT_VALUE = 'No longer supported';
            private const ARGUMENT_DESCRIPTION = 'You can either supply a reason argument ' .
            'with a string value or not supply one and receive a "No longer supported" ' .
            'message when introspected';

            /**
             * class#anonymous constructor.
             * @param Document $document
             * @param DirectiveType $type
             */
            public function __construct(Document $document, DirectiveType $type)
            {
                $this->parent       = $this;
                $this->document     = $document;
                $this->name         = self::ARGUMENT_NAME;
                $this->description  = self::ARGUMENT_DESCRIPTION;
                $this->defaultValue = self::ARGUMENT_DEFAULT_VALUE;
            }

            /**
             * @return Inputable|TypeInterface
             */
            public function getType(): Inputable
            {
                return $this->document->getType(self::ARGUMENT_TYPE);
            }
        };
    }
}
