<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Serializers;

/**
 * Interface SerializerInterface
 * @package App\GraphQL\Serializers
 */
interface SerializerInterface
{
    /**
     * @param object $object
     * @return array
     */
    public function toArray($object): array;
}
