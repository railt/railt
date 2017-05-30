<?php
/**
 * This file is part of Railgun package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Railgun\Support;

use Illuminate\Support\Str;

/**
 * Trait InteractWithName
 * @package Serafim\Railgun\Support
 * @mixin NameableInterface
 */
trait InteractWithName
{
    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @param null|string $name
     * @return NameableInterface|$this
     */
    public function rename(?string $name)
    {
        $this->name = $this->formatName($name);

        return $this;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function formatName(string $name): string
    {
        // Escape fully qualified class names
        $name = array_last(explode('\\', $name));

        // Remove non-writable special chars
        return preg_replace('/\W+/iu', '', $name);
    }

    /**
     * @param null|string $description
     * @return NameableInterface|$this
     */
    public function about(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getDescription(): string
    {
        if ($this->description === null) {
            $this->description = $this->resolveDescription();
        }

        return $this->description;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    private function resolveDescription(): string
    {
        return $this->formatDescription($this->getDescriptionSuffix());
    }

    /**
     * @param string $suffix
     * @return string
     * @throws \ReflectionException
     */
    protected function formatDescription(string $suffix): string
    {
        $prefix = Str::ucfirst(Str::snake($this->getName(), ' '));

        return $prefix . ($suffix ? ' ' . $suffix : '');
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function getName(): string
    {
        if ($this->name === null) {
            $this->name = $this->resolveNameFromDefinition();
        }

        return $this->name;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    private function resolveNameFromDefinition(): string
    {
        $isAnonymous = Str::startsWith(static::class, 'class@anonymous');

        if (! $isAnonymous) {
            return $this->formatName(static::class);
        }

        $reflection = new \ReflectionClass(static::class);

        if ($reflection->getParentClass()) {
            return $this->formatName($reflection->getParentClass()->getShortName());
        }

        return $this->formatName('AnonymousClass');
    }

    /**
     * @return string
     */
    protected function getDescriptionSuffix(): string
    {
        return 'custom definition';
    }
}
