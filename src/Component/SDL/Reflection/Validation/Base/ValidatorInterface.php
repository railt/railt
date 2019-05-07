<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\SDL\Reflection\Validation\Base;

use Railt\Component\SDL\Reflection\Validation\Validator;
use Railt\Component\SDL\Runtime\CallStackInterface;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * ValidatorInterface constructor.
     *
     * @param Validator $validator
     * @param CallStackInterface $stack
     * @param string|null $group
     */
    public function __construct(Validator $validator, CallStackInterface $stack, ?string $group);

    /**
     * @return string
     */
    public function getGroupName(): string;

    /**
     * @param string $group
     * @return ValidatorInterface
     */
    public function getValidator(string $group): self;

    /**
     * @return CallStackInterface
     */
    public function getCallStack(): CallStackInterface;

    /**
     * @param string $exception
     * @param string $message
     * @param array ...$args
     * @return void
     */
    public function throw(string $exception, string $message, ...$args): void;
}
