<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Standard\Scalars;

use Railt\Reflection\Base\BaseScalar;
use Railt\Reflection\Contracts\Document;
use Railt\Reflection\Standard\StandardType;

/**
 * The Float standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-Float
 */
final class FloatType extends BaseScalar implements StandardType
{
    /**
     * The Float scalar public name constant.
     * This name will be used in the future as
     * the type name available for use in our GraphQL schema.
     */
    private const TYPE_NAME = 'Float';

    /**
     * Short Float scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The `Float` scalar type represents signed double-precision fractional
values as specified by [IEEE 754](http://en.wikipedia.org/wiki/IEEE_floating_point).';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->name = self::TYPE_NAME;
        $this->description = self::TYPE_DESCRIPTION;
    }
}
