<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http\Request;

/**
 * Interface QueryTypeInterface
 */
interface QueryTypeInterface
{
    /**
     * @var string
     */
    public const TYPE_QUERY = 'query';

    /**
     * @var string
     */
    public const TYPE_MUTATION = 'mutation';

    /**
     * @var string
     */
    public const TYPE_SUBSCRIPTION = 'subscription';

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return bool
     */
    public function isType(string $type): bool;

    /**
     * @return bool
     */
    public function isQuery(): bool;

    /**
     * @return bool
     */
    public function isMutation(): bool;

    /**
     * @return bool
     */
    public function isSubscription(): bool;
}
