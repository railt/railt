<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Serializers;

use Serafim\Railgun\Example\Models\User;
use Serafim\Railgun\Serializers\AbstractSerializer;

/**
 * Class UserSerializer
 * @package Serafim\Railgun\Example\Serializers
 */
class UserSerializer extends AbstractSerializer
{
    /**
     * @param object|User $user
     * @return array
     */
    public function toArray($user): array
    {
        return [
            'id'       => $user->getId(),
            'name'     => $user->getName(),
            'email'    => $user->getEmail(),

            // Relations
            'comments' => CommentSerializer::collection($user->getComments()),
        ];
    }
}
