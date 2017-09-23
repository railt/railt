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
 * The Boolean standard scalar implementation.
 *
 * @see http://facebook.github.io/graphql/#sec-Boolean
 */
final class BooleanType extends BaseScalar implements StandardType
{
    /**
     * The Boolean scalar public name constant.
     * This name will be used in the future as the
     * type name available for use in our schema.
     */
    private const TYPE_NAME = 'Boolean';

    /**
     * Short Boolean scalar public description.
     */
    private const TYPE_DESCRIPTION = 'The `Boolean` scalar type represents `true` or `false`.';

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
