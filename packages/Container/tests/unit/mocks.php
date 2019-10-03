<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace {

    function __test($argument)
    {
        return $argument;
    }
}

namespace Railt\Container\Tests\Unit {

    function __test($argument)
    {
        return $argument;
    }

    class MockClass
    {
        public function __invoke($argument)
        {
            return $argument;
        }

        public function instanceMethod($argument)
        {
            return $argument;
        }

        public static function staticMethod($argument)
        {
            return $argument;
        }
    }

    class NotCallableClass
    {
    }
}
