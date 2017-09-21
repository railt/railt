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
 * The ID standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-ID
 */
final class IDType extends BaseScalar implements StandardType
{
    /**
     * The ID scalar public name constant.
     * This name will be used in the future as
     * the type name available for use in our GraphQL schema.
     */
    private const TYPE_NAME = 'ID';

    /**
     * Short ID scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The `ID` scalar type represents a unique identifier, often used to
refetch an object or as key for a cache. The ID type appears in a JSON
response as a String; however, it is not intended to be human-readable.
When expected as an input type, any string (such as `"4"`) or integer
(such as `4`) input value will be accepted as an ID.';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document, self::TYPE_NAME);
        $this->description = self::TYPE_DESCRIPTION;
    }
}
