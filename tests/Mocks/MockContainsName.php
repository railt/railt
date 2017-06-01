<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Tests\Mocks;

use Serafim\Railgun\Support\InteractWithName;
use Serafim\Railgun\Support\NameableInterface;

/**
 * Class MockContainsName
 * @package Serafim\Railgun\Tests\Mocks
 */
class MockContainsName implements NameableInterface
{
    use InteractWithName;
}
