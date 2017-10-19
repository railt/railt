<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Base\Processable;

use Railt\Reflection\Base\Definitions\BaseDefinition;
use Railt\Reflection\Base\Resolving;
use Railt\Reflection\Contracts\Definitions\Definition;
use Railt\Reflection\Contracts\Processable\ExtendDefinition;

/**
 * Class BaseExtend
 */
abstract class BaseExtend extends BaseDefinition implements ExtendDefinition
{
    use Resolving;

    /**
     * Extend type name
     */
    protected const TYPE_NAME = 'Extend';

    /**
     * @var Definition
     */
    protected $type;

    /**
     * @return Definition
     */
    public function getRelatedType(): Definition
    {
        return $this->resolve()->type;
    }
}
