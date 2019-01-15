<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Event\Resolver;

use Railt\Foundation\Event\Building\ProvidesTypeDefinition;
use Railt\Foundation\Event\Connection\ProvidesConnection;
use Railt\Foundation\Event\Http\ProvidesRequest;
use Railt\Http\InputInterface;
use Railt\SDL\Contracts\Dependent\FieldDefinition;

/**
 * Interface ResolverEventInterface
 */
interface ResolverEventInterface extends ProvidesConnection, ProvidesRequest, ProvidesTypeDefinition
{
    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface;

    /**
     * @return mixed
     */
    public function getParentResult();

    /**
     * @return mixed
     */
    public function getResult();

    /**
     * @return bool
     */
    public function hasResult(): bool;

    /**
     * @return FieldDefinition
     */
    public function getFieldDefinition(): FieldDefinition;
}
