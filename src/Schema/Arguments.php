<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema;

use Serafim\Railgun\Schema\Creators\ArgumentDefinitionCreator;

/**
 * Class Arguments
 * @package Serafim\Railgun\Schema
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
