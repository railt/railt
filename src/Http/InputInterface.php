<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Http;

use Railt\Http\Input\ProvideArguments;
use Railt\Http\Input\ProvideField;
use Railt\Http\Input\ProvideParents;
use Railt\Http\Input\ProvidePath;
use Railt\Http\Input\ProvideType;

/**
 * Interface InputInterface
 */
interface InputInterface extends ProvideArguments, ProvideType, ProvideParents, ProvideField, ProvidePath
{
    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface;

    /**
     * @param RequestInterface $request
     * @return InputInterface|$this
     */
    public function withRequest(RequestInterface $request): self;

    /**
     * @param string $type
     * @return bool
     */
    public function wants(string $type): bool;

    /**
     * @return iterable|string[]
     */
    public function getPreferTypes(): iterable;

    /**
     * @return string
     */
    public function getPreferType(): string;

    /**
     * @param string ...$types
     * @return InputInterface|$this
     */
    public function withPreferType(string ...$types): self;

    /**
     * @param string ...$types
     * @return InputInterface|$this
     */
    public function setPreferType(string ...$types): self;
}
