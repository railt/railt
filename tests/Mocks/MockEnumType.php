<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Mocks;

use Serafim\Railgun\Types\EnumTypeInterface;
use Serafim\Railgun\Support\InteractWithName;

/**
 * Class MockEnumType
 * @package Serafim\Railgun\Tests\Mocks
 */
class MockEnumType implements EnumTypeInterface
{
    use InteractWithName;
}
