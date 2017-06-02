<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

use Serafim\Railgun\Schema\Creators\CreatorInterface;
use Serafim\Railgun\Schema\Creators\TypeDefinitionCreator;

/**
 * Class BelongsTo
 * @package Serafim\Railgun\Schema
 *
 * @method CreatorInterface|TypeDefinitionCreator typeOf(string $type)
 * @method CreatorInterface|TypeDefinitionCreator listOf(string $type)
 * @method CreatorInterface|TypeDefinitionCreator id()
 * @method CreatorInterface|TypeDefinitionCreator ids()
 * @method CreatorInterface|TypeDefinitionCreator integer()
 * @method CreatorInterface|TypeDefinitionCreator integers()
 * @method CreatorInterface|TypeDefinitionCreator string()
 * @method CreatorInterface|TypeDefinitionCreator strings()
 * @method CreatorInterface|TypeDefinitionCreator boolean()
 * @method CreatorInterface|TypeDefinitionCreator booleans()
 * @method CreatorInterface|TypeDefinitionCreator float()
 * @method CreatorInterface|TypeDefinitionCreator floats()
 */
class BelongsTo extends AbstractSchema
{
    /**
     * Arguments constructor.
     */
    public function __construct()
    {
        parent::__construct(TypeDefinitionCreator::class);
    }
}
