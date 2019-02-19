<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Config;

use Railt\Discovery\Discovery;

/**
 * Class DiscoveryRepository
 */
class DiscoveryRepository extends Repository
{
    /**
     * DiscoveryRepository constructor.
     *
     * @param Discovery|null $discovery
     * @throws \Railt\Io\Exception\NotReadableException
     * @throws \ReflectionException
     */
    public function __construct(Discovery $discovery = null)
    {
        $discovery = $discovery ?? $this->createDiscovery();

        parent::__construct((array)$discovery->get('railt', []));
    }

    /**
     * @return Discovery
     * @throws \ReflectionException
     */
    protected function createDiscovery(): Discovery
    {
        return Discovery::auto();
    }
}
