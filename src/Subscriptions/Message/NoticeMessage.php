<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Message;

/**
 * Class NoticeMessage
 */
class NoticeMessage extends Message
{
    /**
     * NoticeMessage constructor.
     * @param int $id
     * @param string $message
     */
    public function __construct(int $id, string $message)
    {
        parent::__construct([
            static::FIELD_ID   => $id,
            static::FIELD_TYPE => 'notice',
            'message'          => $message,
        ]);
    }
}
