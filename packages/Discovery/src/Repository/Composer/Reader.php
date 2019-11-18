<?php

/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Railt\Discovery\Repository\Composer;

use Composer\Composer;
use Railt\Discovery\Validator\Registry;
use Railt\Discovery\Repository\ReaderInterface;
use Railt\Discovery\Repository\PackageInterface;
use Railt\Discovery\Validator\RegistryInterface;
use Railt\Discovery\Exception\ValidationException;

/**
 * Class Reader
 */
class Reader implements ReaderInterface
{
    /**
     * @var string
     */
    private const DISCOVERY_EXPORT_SECTION = 'discovery';

    /**
     * @var Composer
     */
    private Composer $composer;

    /**
     * @var RegistryInterface
     */
    private RegistryInterface $validator;

    /**
     * @var array|string[]
     */
    private array $sections = [];

    /**
     * ComposerReader constructor.
     *
     * @param Composer $composer
     */
    public function __construct(Composer $composer)
    {
        $this->composer = $composer;
        $this->validator = new Registry();

        $this->loadExportedSections();
    }

    /**
     * @param string $key
     * @param array $schema
     * @return void
     */
    public function shouldValidate(string $key, array $schema): void
    {
        $this->validator->shouldValidate($key, $schema);
    }

    /**
     * @param string $key
     * @param array|object $data
     * @return array|ValidationException[]
     */
    public function validate(string $key, $data): array
    {
        return $this->validator->validate($key, $data);
    }


    /**
     * {@inheritDoc}
     */
    public function getExportedSections(): iterable
    {
        return $this->sections;
    }

    /**
     * @return void
     */
    private function loadExportedSections(): void
    {
        foreach ($this->readExportedSections() as $name => $schema) {
            $this->sections[] = $name;

            if (\is_string($schema)) {
                if (! \is_file($schema) || ! \is_readable($schema)) {
                    throw new \LogicException($schema . ' file not readable');
                }

                $data = \json_decode(\file_get_contents($schema), true, 512, \JSON_THROW_ON_ERROR);

                $this->shouldValidate($name, $data);
            }
        }
    }

    /**
     * @return \Traversable|string[]|null[]
     */
    private function readExportedSections(): \Traversable
    {
        foreach ($this->getPackages() as $package) {
            $extras = $package->getExtra(self::DISCOVERY_EXPORT_SECTION);

            if (\is_array($extras)) {
                foreach ($extras as $key => $value) {
                    if (\is_int($key)) {
                        yield $value => null;
                    } else {
                        yield $key => $value;
                    }
                }
            }
        }
    }

    /**
     * @return iterable|PackageInterface[]
     */
    public function getPackages(): iterable
    {
        $local = $this->composer->getRepositoryManager()->getLocalRepository();

        foreach ($local->getPackages() as $package) {
            yield new Package($this->composer, $package);
        }

        yield new Package($this->composer, $this->composer->getPackage());
    }
}
