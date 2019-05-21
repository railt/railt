<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Validator;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator as JsonValidator;
use Phplrt\Io\Readable;
use Railt\Json\Exception\JsonException;
use Railt\Json\Json5;

/**
 * Class Validator
 */
class Validator implements ValidatorInterface, ValidatorAssertsInterface
{
    use FactoryTrait;

    /**
     * @var int
     */
    public const CHECK_MODE_NONE = Constraint::CHECK_MODE_NONE;

    /**
     * @var int
     */
    public const CHECK_MODE_NORMAL = Constraint::CHECK_MODE_NORMAL;

    /**
     * @var int
     */
    public const CHECK_MODE_TYPE_CAST = Constraint::CHECK_MODE_TYPE_CAST;

    /**
     * @var int
     */
    public const CHECK_MODE_COERCE_TYPES = Constraint::CHECK_MODE_COERCE_TYPES;

    /**
     * @var int
     */
    public const CHECK_MODE_APPLY_DEFAULTS = Constraint::CHECK_MODE_APPLY_DEFAULTS;

    /**
     * @var int
     */
    public const CHECK_MODE_EXCEPTIONS = Constraint::CHECK_MODE_EXCEPTIONS;

    /**
     * @var int
     */
    public const CHECK_MODE_DISABLE_FORMAT = Constraint::CHECK_MODE_DISABLE_FORMAT;

    /**
     * @var int
     */
    public const CHECK_MODE_ONLY_REQUIRED_DEFAULTS = Constraint::CHECK_MODE_ONLY_REQUIRED_DEFAULTS;

    /**
     * @var int
     */
    public const CHECK_MODE_VALIDATE_SCHEMA = Constraint::CHECK_MODE_VALIDATE_SCHEMA;

    /**
     * @var array|object|string
     */
    private $schema;

    /**
     * @var int
     */
    private $mode;

    /**
     * Validator constructor.
     *
     * @param array|object $schema
     * @param int $mode
     */
    public function __construct($schema, int $mode = self::CHECK_MODE_VALIDATE_SCHEMA)
    {
        \assert(\is_array($schema) || \is_object($schema));

        if (! \class_exists(JsonValidator::class)) {
            $this->throwDependencyException('justinrainbow/json-schema');
        }

        $this->schema = $schema;
        $this->mode = $mode;
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
     * @param array|mixed|object $data
     * @return void
     * @throws JsonException
     */
    public function assert($data): void
    {
        $this->validate($data)->throwOnError();
    }

    /**
     * @param array|object|string $data
     * @return ResultInterface
     * @throws JsonException
     */
    public function validate($data): ResultInterface
    {
        if (\is_string($data)) {
            return $this->validateJson($data);
        }

        $validator = new JsonValidator();
        $validator->validate($data, $this->schema, $this->mode);

        return new Result($validator);
    }

    /**
     * @param string $json
     * @return ResultInterface
     * @throws JsonException
     */
    public function validateJson(string $json): ResultInterface
    {
        return $this->validate(Json5::decode($json));
    }

    /**
     * @param Readable $file
     * @return void
     * @throws JsonException
     */
    public function assertFile(Readable $file): void
    {
        $this->validateFile($file)->throwOnError();
    }

    /**
     * @param Readable $file
     * @return ResultInterface
     * @throws JsonException
     */
    public function validateFile(Readable $file): ResultInterface
    {
        return $this->validate($file->getContents());
    }

    /**
     * @param string $json
     * @return void
     * @throws JsonException
     */
    public function assertJson(string $json): void
    {
        $this->validateJson($json)->throwOnError();
    }
}
