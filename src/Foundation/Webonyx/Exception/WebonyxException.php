<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Foundation\Webonyx\Exception;

use GraphQL\Error\Error;
use Railt\Http\Exception\GraphQLException as GraphQLHttpException;
use Railt\Http\Exception\GraphQLExceptionLocation;

/**
 * Class WebonyxException
 */
class WebonyxException extends GraphQLHttpException
{
    /**
     * WebonyxException constructor.
     *
     * @param Error $error
     */
    public function __construct(Error $error)
    {
        $root = $this->getRootException($error);

        parent::__construct($error->getMessage(), $error->getCode(), $root);

        $this->file = $root->getFile();
        $this->line = $root->getLine();

        if ($error->isClientSafe() || $error->getCategory() === Error::CATEGORY_GRAPHQL) {
            $this->publish();
        }

        foreach ($error->getLocations() as $location) {
            $this->addLocation(new GraphQLExceptionLocation($location->line, $location->column));
        }

        foreach ((array)$error->getPath() as $chunk) {
            $this->addPath($chunk);
        }
    }

    /**
     * @param \Throwable $error
     * @return \Throwable
     */
    private function getRootException(\Throwable $error): \Throwable
    {
        while ($error->getPrevious()) {
            $error = $error->getPrevious();
        }

        return $error;
    }
}
