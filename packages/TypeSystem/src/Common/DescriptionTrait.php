<?php

/**
 * This file is part of GraphQL TypeSystem package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\TypeSystem\Common;

use GraphQL\Contracts\TypeSystem\Common\DescriptionAwareInterface;
use Serafim\Immutable\Immutable;

/**
 * @mixin DescriptionAwareInterface
 */
trait DescriptionTrait
{
    /**
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @internal Please note that this method changes the internals of the current
     *           object, and its improper use can violate the integrity of the data.
     *
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @psalm-suppress LessSpecificReturnStatement
     * @psalm-return self
     *
     * @param string|null $description
     * @return object|self|$this
     */
    public function withDescription(?string $description): self
    {
        return Immutable::execute(fn() => $this->setDescription($description));
    }
}
