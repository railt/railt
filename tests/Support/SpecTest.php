<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Tests\Support;

/**
 * Class SpecTest
 * @package Railt\Tests\Support
 */
class SpecTest
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $in;

    /**
     * @var string
     */
    private $out;

    /**
     * @var string
     */
    private $path;

    /**
     * SpecTest constructor.
     * @param string $path
     * @throws \InvalidArgumentException
     */
    public function __construct(string $path)
    {
        $sources = @file_get_contents($this->path = $path);
        if (is_bool($sources)) {
            throw new \InvalidArgumentException('Could not open a Spec file "' . $path . '".');
        }

        $sections = $this->parse($sources);

        if (count($sections) !== 3) {
            throw new \InvalidArgumentException('Could not parse spec file "' . $path . '".');
        }

        [$this->name, $this->in, $this->out] = $sections;
    }

    /**
     * @param string $sources
     * @return array
     */
    private function parse(string $sources): array
    {
        $bodies = array_map('trim', preg_split('/^\-\-(TEST|FILE|EXPECTF)\-\-$/m', $sources));

        return array_values(array_filter($bodies));
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIn(): string
    {
        return $this->in;
    }

    /**
     * @return string
     */
    public function getOut(): string
    {
        return $this->out;
    }
}
