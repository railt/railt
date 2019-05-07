<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json;

use JsonSchema\Exception\ValidationException;
use JsonSchema\Validator as JsonValidator;
use Railt\Component\Io\Readable;
use Railt\Component\Json\Exception\JsonException;
use Railt\Component\Json\Exception\JsonValidationExceptionInterface;
use Railt\Component\Json\Validator\Result;
use Railt\Component\Json\Validator\ResultInterface;

/**
 * Class Validator
 */
class Validator implements ValidatorInterface
{
    /**
     * @var array|object|string
     */
    private $schema;

    /**
     * @var bool
     */
    private $throw = true;

    /**
     * Validator constructor.
     *
     * @param array|object $schema
     */
    public function __construct($schema)
    {
        \assert(\is_array($schema) || \is_object($schema));

        if (! \class_exists(JsonValidator::class)) {
            $this->throwDependencyException('justinrainbow/json-schema');
        }

        $this->schema = $schema;
    }

    /**
     * @param string $dependency
     * @return \LogicException
     */
    private function throwDependencyException(string $dependency): string
    {
        $message = 'The "%s" package is required, make sure the component ' .
            'is installed correctly or use the "composer require %1$s" ' .
            'command to install missing dependency';

        return \sprintf($message, $dependency);
    }

    /**
     * @param bool $enable
     * @return ValidatorInterface
     */
    public function throwOnErrors(bool $enable = true): ValidatorInterface
    {
        $this->throw = $enable;

        return $this;
    }

    /**
     * @param Readable $schema
     * @return ValidatorInterface
     * @throws \JsonException
     */
    public static function fromFile(Readable $schema): ValidatorInterface
    {
        return static::fromData(self::decoder()->read($schema));
    }

    /**
     * @param array|object $schema
     * @return ValidatorInterface
     */
    public static function fromData($schema): ValidatorInterface
    {
        return new static($schema);
    }

    /**
     * @return JsonDecoderInterface
     */
    protected static function decoder(): JsonDecoderInterface
    {
        return Json5::decoder()->setOption(\JSON_OBJECT_AS_ARRAY, false);
    }

    /**
     * @param Readable $file
     * @return ResultInterface
     * @throws JsonException
     * @throws JsonValidationExceptionInterface
     * @throws ValidationException
     * @throws \JsonException
     */
    public function validateFile(Readable $file): ResultInterface
    {
        return $this->validate(self::decoder()->read($file));
    }

    /**
     * @param array|object|string $data
     * @return ResultInterface
     * @throws JsonValidationExceptionInterface
     * @throws JsonException
     * @throws ValidationException
     */
    public function validate($data): ResultInterface
    {
        if (\is_string($data)) {
            return $this->validateJson($data);
        }

        $validator = new JsonValidator();
        $validator->validate($data, $this->schema);

        $result = new Result($validator);

        if ($this->throw) {
            $result->throw();
        }

        return $result;
    }

    /**
     * @param string $json
     * @return ResultInterface
     * @throws JsonException
     * @throws JsonValidationExceptionInterface
     * @throws ValidationException
     */
    public function validateJson(string $json): ResultInterface
    {
        return $this->validate(self::decoder()->decode($json));
    }
}
