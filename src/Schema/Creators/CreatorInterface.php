<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Schema\Creators;

/**
 * Interface CreatorInterface
 * @package Serafim\Railgun\Schema\Creators
 */
interface CreatorInterface
{
    /**
     * @return mixed
     */
    public function build();
}
