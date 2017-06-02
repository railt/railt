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
use Serafim\Railgun\Schema\Creators\ArgumentDefinitionCreator;

/**
 * Class Arguments
 * @package Serafim\Railgun\Schema
 *
 * @method CreatorInterface|ArgumentDefinitionCreator typeOf(string $type)
 * @method CreatorInterface|ArgumentDefinitionCreator listOf(string $type)
 * @method CreatorInterface|ArgumentDefinitionCreator id()
 * @method CreatorInterface|ArgumentDefinitionCreator ids()
 * @method CreatorInterface|ArgumentDefinitionCreator integer()
 * @method CreatorInterface|ArgumentDefinitionCreator integers()
 * @method CreatorInterface|ArgumentDefinitionCreator string()
 * @method CreatorInterface|ArgumentDefinitionCreator strings()
 * @method CreatorInterface|ArgumentDefinitionCreator boolean()
 * @method CreatorInterface|ArgumentDefinitionCreator booleans()
 * @method CreatorInterface|ArgumentDefinitionCreator float()
 * @method CreatorInterface|ArgumentDefinitionCreator floats()
 */
class Arguments extends AbstractSchema
{
    /**
     * Arguments constructor.
     */
    public function __construct()
    {
        parent::__construct(ArgumentDefinitionCreator::class);
    }
}
