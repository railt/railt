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
use Railt\Json\Exception\JsonException;
use Railt\Json\Json5;

/**
 * Trait FactoryTrait
 */
trait FactoryTrait
{
    /**
     * @param Readable $schema
     * @return ValidatorInterface
     * @throws JsonException
     */
    public static function fromFile(Readable $schema): ValidatorInterface
    {
        return static::fromData(Json5::read($schema));
    }

    /**
     * @param array|object $schema
     * @return ValidatorInterface
     */
    public static function fromData($schema): ValidatorInterface
    {
        return new Validator($schema);
    }
}
