<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http\Request;

/**
 * Interface ProvideQueryType
 */
interface ProvideQueryType
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
    public function getQueryType(): string;

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

    /**
     * @param string $type
     * @return ProvideQueryType|$this
     */
    public function withQueryType(string $type): self;
}
