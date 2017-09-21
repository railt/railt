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
 * The String standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-String
 */
final class StringType extends BaseScalar implements StandardType
{
    /**
     * The String scalar public name constant.
     * This name will be used in the future as
     * the type name available for use in our GraphQL schema.
     */
    private const TYPE_NAME = 'String';

    /**
     * Short String scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The `String` scalar type represents textual data, represented as UTF-8
character sequences. The String type is most often used by GraphQL to
represent free-form human-readable text.';

    /**
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        parent::__construct($document, self::TYPE_NAME);
        $this->description = self::TYPE_DESCRIPTION;
    }
}
