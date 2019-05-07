<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Standard\Scalars;

use Railt\SDL\Contracts\Document;
use Railt\SDL\Standard\StandardType;

/**
 * RFC315 Implementation.
 *
 * @see https://github.com/facebook/graphql/pull/315
 * @see https://github.com/graphql/graphql-js/issues/550
 * @see https://github.com/graphql/graphql-js/pull/557
 */
final class DateTimeType extends StringType implements StandardType
{
    /**
     * The DateTime scalar public name constant.
     * This name will be used in the future as the
     * type name available for use in our schema.
     */
    protected const SCALAR_TYPE_NAME = 'DateTime';

    /**
     * Short DateTime scalar public description.
     */
    protected const TYPE_DESCRIPTION = 'The complete set of date and time formats specified in ISO8601 
        is quite complex in an attempt to provide multiple representations and partial representations.';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document);
        $this->deprecationReason = static::RFC_IMPL_DESCRIPTION;
    }

    /**
     * @param mixed|string $value
     * @return bool
     */
    public function isCompatible($value): bool
    {
        if (\is_string($value)) {
            return $this->verifyDate($value);
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function verifyDate($value): bool
    {
        try {
            new \DateTime($value);
            return true;
        } catch (\Throwable $error) {
            return false;
        }
    }
}
