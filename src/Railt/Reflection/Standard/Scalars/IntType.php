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
 * The Float standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-Int
 */
final class IntType extends BaseScalar implements StandardType
{
    /**
     * The Int scalar public name constant.
     * This name will be used in the future as
     * the type name available for use in our GraphQL schema.
     */
    private const TYPE_NAME = 'Int';

    /**
     * Short Int scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The `Int` scalar type represents non-fractional signed whole numeric
values. Int can represent values between -(2^31) and 2^31 - 1.';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document, self::TYPE_NAME);
        $this->description = self::TYPE_DESCRIPTION;
    }
}
