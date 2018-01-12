<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Reflection\Validation\Base;

use Railt\SDL\Kernel\CallStack;
use Railt\SDL\Reflection\Validation\Validator;

/**
 * Class Definitions
 */
abstract class ValidatorsFactory extends Factory
{
    /**
     * Definition validators
     */
    protected const VALIDATOR_CLASSES = [];

    /**
     * Definitions constructor.
     * @param Validator $factory
     * @param CallStack $stack
     * @param null|string $name
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    public function __construct(Validator $factory, CallStack $stack, ?string $name)
    {
        parent::__construct($factory, $stack, $name ?? static::class);

        $this->bootDefaultMatcher();
        $this->bootDefaultValidators();
    }

    /**
     * @return void
     * @throws \InvalidArgumentException
     * @throws \OutOfBoundsException
     */
    private function bootDefaultValidators(): void
    {
        foreach (static::VALIDATOR_CLASSES as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * @return void
     */
    private function bootDefaultMatcher(): void
    {
        $matcher = $this->getDefaultMatcher();

        $this->setMatcher($matcher);
    }

    /**
     * @return \Closure
     */
    abstract protected function getDefaultMatcher(): \Closure;
}
