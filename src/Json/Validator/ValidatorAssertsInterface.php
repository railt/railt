<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Json\Validator;

use Phplrt\Io\Readable;
use Railt\Json\Exception\JsonValidationException;

/**
 * Interface ValidatorAssertsInterface
 */
interface ValidatorAssertsInterface
{
    /**
     * @param array|object|mixed $data
     * @return void
     * @throws JsonValidationException
     */
    public function assert($data): void;

    /**
     * @param Readable $file
     * @return void
     * @throws JsonValidationException
     */
    public function assertFile(Readable $file): void;

    /**
     * @param string $json
     * @return void
     * @throws JsonValidationException
     */
    public function assertJson(string $json): void;
}
