<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Types\Schemas;

use Serafim\Railgun\Contracts\Partials\ArgumentTypeInterface;
use Serafim\Railgun\Types\Creators\ArgumentCreator;


/**
 * Class Arguments
 * @package Serafim\Railgun\Types\Schemas
 *
 * @method ArgumentTypeInterface|ArgumentCreator id()
 * @method ArgumentTypeInterface|ArgumentCreator ids()
 * @method ArgumentTypeInterface|ArgumentCreator integer()
 * @method ArgumentTypeInterface|ArgumentCreator integers()
 * @method ArgumentTypeInterface|ArgumentCreator string()
 * @method ArgumentTypeInterface|ArgumentCreator strings()
 * @method ArgumentTypeInterface|ArgumentCreator boolean()
 * @method ArgumentTypeInterface|ArgumentCreator booleans()
 * @method ArgumentTypeInterface|ArgumentCreator float()
 * @method ArgumentTypeInterface|ArgumentCreator floats()
 *
 */
class Arguments extends AbstractSchema
{
    /**
     * Fields constructor.
     */
    final public function __construct()
    {
        parent::__construct(ArgumentCreator::class);
    }

    /**
     * @param string $name
     * @return ArgumentTypeInterface|ArgumentCreator
     */
    public function typeOf(string $name): ArgumentTypeInterface
    {
        return parent::make($name);
    }

    /**
     * @param string $name
     * @return ArgumentTypeInterface|ArgumentCreator
     */
    public function listOf(string $name): ArgumentTypeInterface
    {
        return parent::list($name);
    }

}
