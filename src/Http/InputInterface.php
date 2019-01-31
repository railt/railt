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
use Railt\Http\Input\ProvidePreferTypes;
use Railt\Http\Input\ProvideType;

/**
 * Interface InputInterface
 */
interface InputInterface extends
    ProvidePath,
    ProvideType,
    ProvideField,
    ProvideParents,
    ProvideArguments,
    ProvidePreferTypes
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
}
