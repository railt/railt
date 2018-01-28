<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\SDL\Compiler;

use Railt\Compiler\Ast\NodeInterface;
use Railt\Compiler\Ast\RuleInterface;
use Railt\SDL\Compiler\Pipeline\Builder;
use Railt\SDL\Compiler\Pipeline\Collector;
use Railt\SDL\Compiler\Pipeline\Pipe;
use Railt\SDL\Compiler\Pipeline\Stage;

/**
 * Class Pipeline
 */
class Pipeline
{
    /**+@#
     * Pipeline state list
     */
    public const STATE_COLLECT = 0x00;
    public const STATE_BUILDING = 0x01;
    public const STATE_COERCION = 0x02;
    public const STATE_VALIDATION = 0x03;
    public const STATE_EXTENSIONS = 0x04; // -> 2
    public const STATE_INVOCATIONS = 0x05; // -> 2
    /**-@#*/

    /**
     * @var array|Stage[]
     */
    private $stages;

    /**
     * Pipeline constructor.
     */
    public function __construct()
    {
        $this->stages = \SplFixedArray::fromArray([
            self::STATE_COLLECT     => new Collector(),
            self::STATE_BUILDING    => new Builder($this), // TODO
            self::STATE_COERCION    => new Pipe(), // TODO
            self::STATE_VALIDATION  => new Pipe(), // TODO
            self::STATE_EXTENSIONS  => new Pipe(), // TODO
            self::STATE_INVOCATIONS => new Pipe(), // TODO
        ]);
    }

    /**
     * @param int $stageId
     * @param $data
     * @return $this
     */
    public function push(int $stageId, $data)
    {
        $stageId = \min(self::STATE_INVOCATIONS, $stageId);
        $stageId = \max(self::STATE_COLLECT, $stageId);

        $this->stages[$stageId]->push($data);

        return $this;
    }

    /**
     * @param $data
     * @return Pipeline
     */
    public function add($data)
    {
        switch (true) {
            case $data instanceof NodeInterface:
                return $this->push(self::STATE_COLLECT, $data);
        }

        // Error
    }

    /**
     * @param RuleInterface $ast
     * @return mixed
     */
    public function process(RuleInterface $ast)
    {
        $this->add($ast);
        $data = null;

        boot:
        foreach ($this->stages as $id => $stage) {
            if (! $stage->isEmpty()) {
                $data = $this->resolve($id, $stage);
                goto boot; // RAAAAAAAAAAWRRRRRRRRR!!11111 (c) Dinosaur
            }
        }

        return $data;
    }

    /**
     * @param int $id
     * @param Stage $stage
     * @return mixed
     */
    private function resolve(int $id, Stage $stage)
    {
        $data = null;

        foreach ($stage->resolve() as $data) {
            if ($id < self::STATE_INVOCATIONS) {
                $this->push($id + 1, $data);
            }
        }

        return $data;
    }
}
