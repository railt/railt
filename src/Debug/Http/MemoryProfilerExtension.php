<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Debug\Http;

use Railt\Http\Extension\ExtensionInterface;

/**
 * Class MemoryProfilerExtension
 */
class MemoryProfilerExtension implements ExtensionInterface
{
    /**
     * @return array
     */
    public function getValue(): array
    {
        return [
            'memory' => [
                'current' => $this->kBytes(\memory_get_usage()),
                'peak'    => $this->kBytes(\memory_get_peak_usage()),
            ],
        ];
    }

    /**
     * @param int $bytes
     * @return string
     */
    private function kBytes(int $bytes): string
    {
        return \number_format($bytes / 1024, 2) . 'Kb';
    }
}
