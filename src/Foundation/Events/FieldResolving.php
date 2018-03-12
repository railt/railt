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
use Railt\Routing\Store\ObjectBox;

/**
 * Class FieldResolving
 */
class FieldResolving extends BaseFieldResolver
{
    /**
     * @var ObjectBox|null
     */
    private $parent;

    /**
     * @var mixed
     */
    private $response;

    /**
     * FieldResolving constructor.
     * @param InputInterface $input
     * @param ObjectBox|null $parent
     */
    public function __construct(InputInterface $input, ?ObjectBox $parent)
    {
        parent::__construct($input);
        $this->parent = $parent;
    }

    /**
     * @return ObjectBox|null
     */
    public function getParentValue(): ?ObjectBox
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
