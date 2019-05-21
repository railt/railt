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
 * Interface FactoryInterface
 */
interface FactoryInterface
{
    /**
     * @param object|array $schema
     * @return ValidatorInterface
     */
    public static function fromData($schema): ValidatorInterface;

    /**
     * @param Readable $schema
     * @return ValidatorInterface
     */
    public static function fromFile(Readable $schema): ValidatorInterface;
}
