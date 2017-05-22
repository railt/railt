<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Example\Serializers;

use Serafim\Railgun\Example\Models\Comment;
use Serafim\Railgun\Serializers\AbstractSerializer;

/**
 * Class CommentSerializer
 * @package Serafim\Railgun\Example\Serializers
 */
class CommentSerializer extends AbstractSerializer
{
    /**
     * @param object|Comment $comment
     * @return array
     */
    public function toArray($comment): array
    {
        return [
            'id'   => $comment->getId(),
            'body' => $comment->getBody(),
        ];
    }
}
