<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Adapters\Webonyx\Builders;

/**
 * Interface BuilderInterface
 * @package Serafim\Railgun\Adapters\Webonyx\Builders
 */
interface BuilderInterface
{
    /**
     * BuilderInterface constructor.
     * @param null|string $context
     */
    public function __construct(?string $context);

    /**
     * @return mixed
     */
    public function build();
}
