<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Component\Http;

use Railt\Component\Http\Input\ProvideArguments;
use Railt\Component\Http\Input\ProvideField;
use Railt\Component\Http\Input\ProvideParents;
use Railt\Component\Http\Input\ProvidePath;
use Railt\Component\Http\Input\ProvidePreferTypes;
use Railt\Component\Http\Input\ProvideRelatedFields;
use Railt\Component\Http\Input\ProvideType;

/**
 * Interface InputInterface
 */
interface InputInterface extends
    ProvidePath,
    ProvideType,
    ProvideField,
    ProvideParents,
    ProvideArguments,
    ProvidePreferTypes,
    ProvideRelatedFields
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
