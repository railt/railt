<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Reflection\Builder\Support;

use Ramsey\Uuid\Uuid;

/**
 * Class Identifier
 */
class Identifier
{
    /**
     * @return string
     * @throws \Exception
     */
    private static function factory(): string
    {
        /**
         * Create identifier from COM extension
         * @see http://php.net/manual/ru/function.com-create-guid.php
         */
        if (\extension_loaded('com_dotnet')) {
            return self::fromGuid();
        }

        /**
         * Ramsey UUID generator
         * @see https://github.com/ramsey/uuid
         */
        if (\class_exists(Uuid::class)) {
            return self::fromRamsey();
        }

        return self::fromRandomBytes();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function generate(): string
    {
        return \strtolower(self::factory());
    }

    /**
     * @return string
     */
    private static function fromGuid(): string
    {
        return \trim(\com_create_guid(), '{}');
    }

    /**
     * @return string
     */
    private static function fromRamsey(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return string
     * @throws \Exception
     */
    private static function fromRandomBytes(): string
    {
        $data = \random_bytes(16);

        $data{6} = \chr(\ord($data{6} ?? 'x') & 0x0f | 0x40); // set version to 0100
        $data{8} = \chr(\ord($data{8} ?? 'x') & 0x3f | 0x80); // set bits 6-7 to 10

        return \vsprintf('%s%s-%s-%s-%s-%s%s%s', \str_split(\bin2hex($data), 4));
    }
}
