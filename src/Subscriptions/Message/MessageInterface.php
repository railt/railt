<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\Message;

use Railt\Http\Identifiable;

/**
 * Interface MessageInterface
 */
interface MessageInterface extends Identifiable
{
    /**
     * @var string
     */
    public const TYPE_ANSWER = 'data';

    /**
     * @var string
     */
    public const TYPE_START = 'start';

    /**
     * @var string
     */
    public const TYPE_STOP = 'stop';

    /**
     * @var string
     */
    public const TYPE_PING = 'connection_ack';

    /**
     * @param string $type
     * @return bool
     */
    public function is(string $type): bool;

    /**
     * @param string|int|float $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null);
}
