<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Directives;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Contracts\Types\DirectiveType;
use Railt\Reflection\Standard\Base\BaseDirective;
use Railt\Reflection\Standard\Base\Directive\Argument;

/**
 * Class Deprecation
 *
 * @see https://github.com/graphql/graphql-js/pull/384
 */
class Deprecation extends BaseDirective
{
    /**
     *
     */
    private const TYPE_NAME = 'deprecated';

    /**
     *
     */
    private const ARG_DEPRECATION_REASON_NAME = 'reason';

    /**
     * Deprecation constructor.
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document, self::TYPE_NAME);

        $this->deprecationReason = static::RFC_IMPL_DESCRIPTION;

        $this->addArgument($this->createArgument(
            self::ARG_DEPRECATION_REASON_NAME,
            'No longer supported'
        ));
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return Argument
     */
    private function createArgument(string $name, $default): Argument
    {
        return (new class($this->getDocument(), $name, $this) extends Argument
        {
            /**
             * class#anonymous constructor.
             * @param Document $document
             * @param string $name
             * @param DirectiveType $type
             */
            public function __construct(Document $document, string $name, DirectiveType $type)
            {
                parent::__construct($document, $name, $type);
            }

            /**
             * @param mixed $value
             * @return $this|Argument
             */
            public function setDefaultValue($value): Argument
            {
                $this->defaultValue = $value;

                return $this;
            }
        })
            ->setDefaultValue($default);
    }
}
