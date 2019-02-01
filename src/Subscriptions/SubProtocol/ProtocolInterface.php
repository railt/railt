<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Subscriptions\SubProtocol;

use Railt\Subscriptions\Message\MessageInterface;
use Railt\Http\Identifiable;

/**
 * Interface ProtocolInterface
 */
interface ProtocolInterface extends Identifiable
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @param MessageInterface $message
     */
    public function handle(MessageInterface $message): void;

    /**
     * @param \Closure $then
     */
    public function onAnswer(\Closure $then): void;

    /**
     * @return void
     */
    public function notify(): void;

    /**
     * @return void
     */
    public function close(): void;
}
