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

/**
 * Interface ValidatorInterface
 */
interface ValidatorInterface
{
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
