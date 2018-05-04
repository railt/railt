<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Testing;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Railt\Testing\Common\MethodsAccess;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    use MethodsAccess;
    use Feature\InteractWithServer;
}
