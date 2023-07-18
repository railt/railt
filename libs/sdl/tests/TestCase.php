<?php

declare(strict_types=1);

namespace Railt\SDL\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('unit'), Group('sdl')]
abstract class TestCase extends BaseTestCase
{
}
