<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Events;

use Railt\Http\InputInterface;
use Railt\Routing\Store\Box;

/**
 * Class FieldResolving
 */
class FieldResolving extends BaseFieldResolver
{
    /**
     * @var Box|null
     */
    private $parent;

    /**
     * @var mixed
     */
    private $response;

    /**
     * FieldResolving constructor.
     * @param InputInterface $input
     * @param Box|null $parent
     */
    public function __construct(InputInterface $input, ?Box $parent)
    {
        parent::__construct($input);
        $this->parent = $parent;
    }

    /**
     * @return Box|null
     */
    public function getParentValue(): ?Box
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $value
     * @return FieldResolving
     */
    public function setResponse($value): self
    {
        $this->response = $value;

        return $this;
    }
}
