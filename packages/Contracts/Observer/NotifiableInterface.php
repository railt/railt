<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Contracts\Observer;

/**
 * Interface NotifiableInterface
 */
interface NotifiableInterface
{
    /**
     * Notifies all observers of an update from this subject.
     */
    public function notify(): void;
}
