<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railgun\Routing;

/**
 * Class Schema
 * @package Railgun\Routing
 */
class Schema
{
    /**
     * @param callable|string $relation
     * @return Respondent
     */
    public function belongsTo($relation): Respondent
    {
        return Respondent::new($relation);
    }
}
