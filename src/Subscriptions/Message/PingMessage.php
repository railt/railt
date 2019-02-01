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
 * Class ActiveMessage
 */
class PingMessage extends Message
{
    /**
     * ActiveMessage constructor.
     */
    public function __construct()
    {
        parent::__construct([
            static::FIELD_TYPE => static::TYPE_PING,
        ]);
    }
}
