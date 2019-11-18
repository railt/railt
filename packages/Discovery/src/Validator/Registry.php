<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery\Validator;

use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;
use Railt\Discovery\Exception\ValidationException;

/**
 * Class Registry
 */
class Registry implements RegistryInterface
{
    /**
     * @var string
     */
    private const VALIDATOR_MODE = Constraint::CHECK_MODE_VALIDATE_SCHEMA;

    /**
     * @var array
     */
    private array $payload = [];

    /**
     * @param string $key
     * @param array $schema
     * @return void
     */
    public function shouldValidate(string $key, array $schema): void
    {
        $this->payload[$key][] = $schema;
    }

    /**
     * @param string $key
     * @param object|array $data
     * @return array
     */
    public function validate(string $key, $data): array
    {
        $result = [];

        foreach ($this->payload[$key] ?? [] as $schema) {
            $validator = new Validator();
            $validator->validate($data, $schema, self::VALIDATOR_MODE);

            foreach ($validator->getErrors() as $error) {
                $result[] = new ValidationException($error['message'], $key, $error['pointer']);
            }
        }

        return $result;
    }
}
