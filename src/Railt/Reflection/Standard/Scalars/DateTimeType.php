<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Scalars;

use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Standard\Base\BaseScalar;
use Railt\Reflection\Standard\StandardType;

/**
 * RFC315 Implementation.
 *
 * @see https://github.com/facebook/graphql/pull/315
 * @see https://github.com/graphql/graphql-js/issues/550
 * @see https://github.com/graphql/graphql-js/pull/557
 */
final class DateTimeType extends BaseScalar implements StandardType
{
    /**
     * The DateTime scalar public name constant.
     * This name will be used in the future as the
     * type name available for use in our schema.
     */
    private const TYPE_NAME = 'DateTime';

    /**
     * Short DateTime scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The complete set of date and time formats specified in ISO8601 
        is quite complex in an attempt to provide multiple representations and partial representations.';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document, self::TYPE_NAME);

        [$this->description, $this->deprecationReason] =
            [self::TYPE_DESCRIPTION, self::RFC_IMPL_DESCRIPTION];
    }
}
