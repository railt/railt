<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Composer;

use Railt\Discovery\Parser\ParserInterface;
use Railt\Json\Exception\JsonException;
use Railt\Json\Json;
use Railt\Json\Validator\ResultInterface;
use Railt\Json\ValidatorInterface;

/**
 * Class Section
 */
class Section
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var Package
     */
    private $package;

    /**
     * Section constructor.
     *
     * @param Package $package
     * @param ParserInterface $parser
     * @param string $name
     * @param $data
     */
    public function __construct(Package $package, ParserInterface $parser, string $name, $data)
    {
        $this->package = $package;
        $this->parser = $parser;
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return DiscoverySection
     */
    public function getConfiguration(): DiscoverySection
    {
        return new DiscoverySection($this->package, $this);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ValidatorInterface $validator
     * @return ResultInterface
     * @throws JsonException
     */
    public function validate(ValidatorInterface $validator): ResultInterface
    {
        return $validator->validate($this->getData());
    }

    /**
     * @param iterable $validators
     * @throws JsonException
     */
    public function validateAll(iterable $validators): void
    {
        foreach ($validators as $validator) {
            $this->validate($validator);
        }
    }

    /**
     * @return array|mixed|object
     * @throws JsonException
     */
    public function getData()
    {
        return Json::decoder()
            ->setOption(\JSON_OBJECT_AS_ARRAY, false)
            ->decode($this->getJson());
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return Json::encode($this->get());
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->parser->bypass($this->data);
    }
}
