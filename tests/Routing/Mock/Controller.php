<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Routing\Mock;

/**
 * Class Controller
 */
class Controller
{
    /**
     * @param string|null $value
     * @return string|null
     */
    public function scalar(string $value = null): ?string
    {
        return $value ?? 'default';
    }
}
