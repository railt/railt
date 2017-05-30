<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Schemas;

use Serafim\Railgun\Schema\Arguments;
use Serafim\Railgun\Schema\BelongsTo;
use Serafim\Railgun\Schema\Fields;
use Serafim\Railgun\Schema\Registry;
use Serafim\Railgun\Schema\SchemaInterface;

/**
 * Class BelongsToSchemaTestCase
 * @package Serafim\Railgun\Tests\Schemas
 */
class BelongsToSchemaTestCase extends AbstractDefinitionsTestCase
{
    /**
     * @return SchemaInterface
     * @throws \InvalidArgumentException
     */
    protected function getSchema(): SchemaInterface
    {
        return (new Registry())->get(BelongsTo::class);
    }
}
