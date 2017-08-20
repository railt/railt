<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Example;

use Railgun\Adapters\RequestInterface;

/**
 * Class SupportController
 * @package Example
 */
class SupportController
{
    /**
     * @param RequestInterface $request
     * @param \DateTimeInterface $time
     * @return string
     */
    public function dateTime(RequestInterface $request, \DateTimeInterface $time): string
    {
        $key    = 'DateTime::' . $request->get('format');
        $format = defined($key) ? constant($key) : \DateTime::RFC3339;

        return $time->format($format);
    }
}
