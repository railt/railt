<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Validation\Base;

use Railt\Compiler\Kernel\CallStack;
use Railt\Compiler\Reflection\Validation\Validator;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * ValidatorInterface constructor.
     * @param Validator $validator
     * @param CallStack $stack
     * @param string|null $group
     */
    public function __construct(Validator $validator, CallStack $stack, ?string $group);

    /**
     * @return string
     */
    public function getGroupName(): string;

    /**
     * @param string $group
     * @return ValidatorInterface
     */
    public function getValidator(string $group): ValidatorInterface;

    /**
     * @return CallStack
     */
    public function getCallStack(): CallStack;

    /**
     * @param string $exception
     * @param string $message
     * @param array ...$args
     * @return void
     */
    public function throw(string $exception, string $message, ...$args): void;
}
