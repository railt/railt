<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Validator;

use Railt\Json\Exception\JsonValidationExceptionInterface;

/**
 * Interface ResultInterface
 */
interface ResultInterface
{
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @return iterable|JsonValidationExceptionInterface[]
     */
    public function getErrors(): iterable;
}
