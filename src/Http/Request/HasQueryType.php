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
 * Trait HasQueryType
 * @mixin ProvideQueryType
 */
trait HasQueryType
{
    /**
     * @var string
     */
    private $queryType = self::TYPE_QUERY;

    /**
     * @return string
     */
    public function getQueryType(): string
    {
        return $this->queryType;
    }

    /**
     * @return bool
     */
    public function isQuery(): bool
    {
        return $this->queryType === ProvideQueryType::TYPE_QUERY;
    }

    /**
     * @return bool
     */
    public function isMutation(): bool
    {
        return $this->queryType === ProvideQueryType::TYPE_MUTATION;
    }

    /**
     * @return bool
     */
    public function isSubscription(): bool
    {
        return $this->queryType === ProvideQueryType::TYPE_SUBSCRIPTION;
    }

    /**
     * @param string $type
     * @return ProvideQueryType|$this
     */
    public function withQueryType(string $type): ProvideQueryType
    {
        $this->queryType = \mb_strtolower($type);

        return $this;
    }
}
