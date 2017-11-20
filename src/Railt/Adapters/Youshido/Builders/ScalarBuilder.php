<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Adapters\Youshido\Builders;

use Railt\Adapters\AdapterInterface;
use Railt\Reflection\Contracts\Definitions\ScalarDefinition;
use Youshido\GraphQL\Type\Scalar\AbstractScalarType;

/**
 * TODO
 */
class ScalarBuilder extends AbstractScalarType
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @var ScalarDefinition
     */
    private $type;

    /**
     * ScalarBuilder constructor.
     * @param AdapterInterface $adapter
     * @param ScalarDefinition $type
     */
    public function __construct(AdapterInterface $adapter, ScalarDefinition $type)
    {
        $this->adapter = $adapter;
        $this->type = $type;
    }
}
