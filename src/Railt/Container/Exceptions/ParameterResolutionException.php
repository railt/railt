<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Container\Exceptions;

/**
 * Class ParameterResolutionException
 */
class ParameterResolutionException extends ContainerResolutionException
{
    /**
     * ParameterResolutionException constructor.
     * @param \ReflectionParameter $parameter
     */
    public function __construct(\ReflectionParameter $parameter)
    {
        $error = 'Can not resolve parameter %s([#%s => %s])';

        $error = sprintf(
            $error,
            $this->context($parameter),
            $parameter->getPosition(),
            $this->getParameterDefinition($parameter)
        );

        parent::__construct($error);
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    private function context(\ReflectionParameter $parameter): string
    {
        $prefix = '';
        $method = $parameter->getDeclaringFunction();

        if ($method instanceof \ReflectionMethod) {
            $prefix = $method->getDeclaringClass()->getName() . '::';
        }

        return $prefix . $method->getName();
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    private function getParameterDefinition(\ReflectionParameter $parameter): string
    {
        return $this->getTypePrefix($parameter) . '$' . $parameter->getName();
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    private function getTypePrefix(\ReflectionParameter $parameter): string
    {
        if ($parameter->hasType()) {
            return $parameter->getType()->getName() . ' ';
        }

        if ($parameter->getClass()) {
            return $parameter->getClass()->getName() . ' ';
        }

        return '';
    }
}
