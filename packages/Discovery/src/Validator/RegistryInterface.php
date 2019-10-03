<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Validator;

use Railt\Discovery\Exception\ValidationException;

/**
 * Interface RegistryInterface
 */
interface RegistryInterface
{
    /**
     * @param string $key
     * @param array $schema
     * @return void
     */
    public function shouldValidate(string $key, array $schema): void;

    /**
     * @param string $key
     * @param object|array $data
     * @return array|ValidationException[]
     */
    public function validate(string $key, $data): array;
}
