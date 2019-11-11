<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Feature\Http;

use Behat\Behat\Context\Context;
use Railt\Tests\Feature\Http\RequestFeatureContext\RequestFeatureTrait;
use Railt\Tests\Feature\Http\Support\DepthTrait;
use Railt\Tests\Feature\Http\Support\NumericalTrait;
use Railt\Tests\Feature\Http\Support\TypeCastTrait;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    use DepthTrait;
    use TypeCastTrait;
    use NumericalTrait;
    use RequestFeatureTrait;
}
