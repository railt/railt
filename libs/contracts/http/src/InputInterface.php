<?php

declare(strict_types=1);

namespace Railt\Contracts\Http;

use Railt\Contracts\Http\Input\ArgumentsProviderInterface;
use Railt\Contracts\Http\Input\PathProviderInterface;

/**
 * @template TDefinition of object
 */
interface InputInterface extends
    PathProviderInterface,
    ArgumentsProviderInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;

    /**
     * @return non-empty-string|null
     */
    public function getAlias(): ?string;

    public function getRequest(): RequestInterface;

    /**
     * @return TDefinition
     */
    public function getDefinition(): object;
}
