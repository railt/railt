<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Tests\Feature;

use Behat\Behat\Context\Context;
use Railt\Http\Tests\Feature\Support\DepthTrait;
use Railt\Http\Tests\Feature\Support\TypeCastTrait;
use Railt\Http\Tests\Feature\Support\NumericalTrait;
use Railt\Http\Tests\Feature\RequestFeatureContext\RequestFeatureTrait;

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
