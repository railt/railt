<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Json;

use Railt\Component\Io\Readable;
use Railt\Component\Json\Validator\ResultInterface;

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
    /**
     * @param object|array $schema
     * @return ValidatorInterface
     */
    public static function fromData($schema): self;

    /**
     * @param Readable $schema
     * @return ValidatorInterface
     */
    public static function fromFile(Readable $schema): self;

    /**
     * @param bool $enable
     * @return ValidatorInterface
     */
    public function throwOnErrors(bool $enable = true): self;

    /**
     * @param array|object|mixed $data
     * @return ResultInterface
     */
    public function validate($data): ResultInterface;

    /**
     * @param Readable $file
     * @return ResultInterface
     */
    public function validateFile(Readable $file): ResultInterface;

    /**
     * @param string $json
     * @return ResultInterface
     */
    public function validateJson(string $json): ResultInterface;
}
