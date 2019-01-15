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
use Railt\Testing\Feature\BootableTraits;
use Railt\Testing\Interact\InteractWithApplication;
use Railt\Testing\Interact\InteractWithEnvironment;

/**
 * Class TestCase
 */
abstract class TestCase extends BaseTestCase
{
    use BootableTraits;
    use InteractWithApplication;
    use InteractWithEnvironment;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->setUpTestKernel();

        parent::setUp();
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->tearDownTestKernel();

        parent::tearDown();
    }
}
