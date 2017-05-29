<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Definitions;

use Serafim\Railgun\Support\NameableInterface;

/**
 * Interface ArgumentDefinitionInterface
 * @package Serafim\Railgun\Schema\Definitions
 */
interface ArgumentDefinitionInterface extends
    NameableInterface,
    ProvidesTypeDefinitionInterface
{
    /**
     * @return mixed
     */
    public function getDefaultValue();
}
