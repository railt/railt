<?php

declare(strict_types=1);

namespace Railt\Contracts\Http\Response;

interface DataProviderInterface
{
    /**
     * @return array|null
     */
    public function getData(): ?array;

    /**
     * Returns new instance of {@see DataProviderInterface} with the passed data.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that contains the
     *                  specified data.
     */
    public function withData(array $data): self;

    /**
     * Returns new instance of {@see DataProviderInterface} without the data.
     *
     * @psalm-immutable This method MUST retain the state of the current
     *                  instance, and return an instance that does not contain
     *                  the data.
     */
    public function withoutData(): self;
}
