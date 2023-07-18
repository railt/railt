<?php

declare(strict_types=1);

namespace Railt\TypeSystem\Tests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase as BaseTestCase;

#[Group('unit'), Group('type-system')]
abstract class TestCase extends BaseTestCase
{
}
