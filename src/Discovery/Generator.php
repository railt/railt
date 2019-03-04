<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Discovery;

use Composer\Composer;
use Composer\IO\IOInterface;
use Railt\Discovery\Composer\DiscoveryConfiguration;
use Railt\Discovery\Composer\DiscoverySection;
use Railt\Discovery\Composer\Package;
use Railt\Discovery\Composer\Reader;
use Railt\Discovery\Composer\Section;
use Railt\Discovery\Exception\ValidationException;
use Railt\Io\Readable;
use Railt\Json\Exception\JsonValidationExceptionInterface;
use Railt\Json\Json;

/**
 * Class Generator
 */
class Generator
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * Generator constructor.
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function __construct(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @param array $data
     * @return Readable
     * @throws \RuntimeException
     */
    public function save(array $data): Readable
    {
        $config = $this->composer->getConfig();
        $directory = $config->get('vendor-dir');

        return Json::write($directory . '/discovery.json', $data);
    }

    /**
     * @return array
     * @throws ValidationException
     * @throws \Throwable
     */
    public function run(): array
    {
        $reader = new Reader($this->composer);

        $sections = [];

        /**
         * @var Section $section
         * @var DiscoveryConfiguration[] $configs
         */
        foreach ($this->collect($reader, $this->io) as $section => $configs) {
            $name = $section->getName();

            if (! isset($sections[$name])) {
                $sections[$name] = [];
            }

            $value = $section->get();

            foreach ($configs as $config) {
                $value = $config->filter($value);
            }

            /** @noinspection SlowArrayOperationsInLoopInspection */
            $sections[$name] = \array_merge_recursive($sections[$name], $value);
        }

        return $sections;
    }

    /**
     * @param Reader $reader
     * @param IOInterface $io
     * @return \Traversable|DiscoveryConfiguration[][]
     * @throws ValidationException
     * @throws \Throwable
     */
    private function collect(Reader $reader, IOInterface $io): \Traversable
    {
        $sections = $this->loadConfigs($reader);

        foreach ($sections as $name => $configs) {
            $io->write(\sprintf('Discovery: <info>%s</info>', $name));

            /**
             * @var Package $package
             * @var Section $section
             */
            foreach ($this->readSection($name, $reader) as $package => $section) {
                $io->write(\sprintf('    import from <comment>%s</comment>: ', $package->getName()), false);

                try {
                    foreach ($configs as $i => $config) {
                        $config->validate($section);
                    }
                    $io->write('<info>OK</info>');
                } catch (JsonValidationExceptionInterface $e) {
                    $io->write('<error> ERROR: ' . $e->getMessage() . ' </error>');
                    throw ValidationException::fromJsonException($e, $package, $section);
                } catch (\Throwable $e) {
                    $io->write('<error> ERROR </error>');

                    throw $e;
                }

                yield $section => $configs;
            }
        }
    }

    /**
     * @param string $name
     * @param Reader $reader
     * @return \Traversable
     */
    private function readSection(string $name, Reader $reader): \Traversable
    {
        foreach ($reader->getPackages() as $package) {
            $section = $package->getSection($name);

            if ($section) {
                yield $package => $section;
            }
        }
    }

    /**
     * @param Reader $reader
     * @return array|DiscoveryConfiguration[][]
     */
    private function loadConfigs(Reader $reader): array
    {
        $sections = [];

        foreach ($this->readConfigs($reader) as $name => $config) {
            if (! isset($sections[$name])) {
                $sections[$name] = [];
            }

            $sections[$name][] = $config;
        }

        return $sections;
    }

    /**
     * @param Reader $reader
     * @return \Traversable|DiscoveryConfiguration[]
     */
    private function readConfigs(Reader $reader): \Traversable
    {
        foreach ($reader->getPackages() as $package) {
            $section = $package->getSection(DiscoverySection::KEY_DISCOVERY);

            if ($section !== null) {
                yield from $section->getConfiguration();
            }
        }
    }
}
