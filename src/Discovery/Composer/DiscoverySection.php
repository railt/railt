<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery\Composer;

use Railt\Discovery\Exception\ConfigurationException;
use Railt\Discovery\Exception\ValidationException;
use Railt\Io\File;
use Railt\Json\Exception\JsonValidationExceptionInterface;
use Railt\Json\Validator;
use Railt\Json\ValidatorInterface;

/**
 * Class DiscoverySection
 */
class DiscoverySection implements \IteratorAggregate
{
    /**
     * @var string
     */
    public const KEY_DISCOVERY = 'discovery';

    /**
     * @var string
     */
    private const JSON_SCHEMA_CONFIG_FILE = __DIR__ . '/../../resources/discovery.schema.json5';

    /**
     * @var Section
     */
    private $section;

    /**
     * @var Package
     */
    private $package;

    /**
     * DiscoverySection constructor.
     *
     * @param Package $package
     * @param Section $section
     */
    public function __construct(Package $package, Section $section)
    {
        $this->section = $section;
        $this->package = $package;
    }

    /**
     * @return \Generator|\Traversable
     * @throws ValidationException
     */
    public function getIterator()
    {
        try {
            $this->section->validate($this->getDiscoveryValidator());

            foreach ($this->section->get() as $name => $configs) {
                [$name, $configs] = \is_int($name) ? [$configs, null] : [$name, $configs];

                yield $name => new DiscoveryConfiguration((array)$configs);
            }
        } catch (JsonValidationExceptionInterface $e) {
            throw ConfigurationException::fromJsonException($e, $this->package, $this->section);
        } catch (\Throwable $e) {
            throw ConfigurationException::fromException($e, $this->package);
        }
    }

    /**
     * @return ValidatorInterface
     * @throws \JsonException
     * @throws \Railt\Io\Exception\NotReadableException
     */
    public function getDiscoveryValidator(): ValidatorInterface
    {
        $file = File::fromPathname(self::JSON_SCHEMA_CONFIG_FILE);

        return Validator::fromFile($file);
    }
}
