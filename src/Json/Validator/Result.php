<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Validator;

use JsonSchema\Validator;
use Railt\Json\Exception\JsonValidationException;
use Railt\Json\Exception\JsonValidationExceptionInterface;

/**
 * Class Result
 */
class Result implements ResultInterface
{
    /**
     * @var bool
     */
    private $valid;

    /**
     * @var iterable
     */
    private $errors;

    /**
     * Result constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->valid = $validator->isValid();
        $this->errors = $validator->getErrors();
    }

    /**
     * @throws JsonValidationExceptionInterface
     */
    public function throw(): void
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->getErrors() as $error) {
            throw $error;
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return \count($this->errors) > 0;
    }

    /**
     * @return iterable|JsonValidationExceptionInterface[]
     */
    public function getErrors(): iterable
    {
        foreach ($this->errors as $error) {
            $path = \explode('.', $error['property']);

            yield new JsonValidationException($error['message'], $path);
        }
    }
}
