<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Extension\Debug\Clockwork;

use Clockwork\Clockwork;
use Clockwork\Request\UserData;
use Railt\Container\Container;
use Railt\Foundation\Event\Http\ResponseProceed;
use Railt\SDL\Reflection\Dictionary;
use Railt\SDL\Standard\StandardType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtSchemaSubscriber
 */
class RailtSchemaSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    private $app;

    /**
     * @var UserData
     */
    private $data;

    /**
     * FieldResolveSubscriber constructor.
     *
     * @param Clockwork $clockwork
     * @param Container $app
     */
    public function __construct(Clockwork $clockwork, Container $app)
    {
        $this->app = $app;

        $this->data = $clockwork
            ->userData('railt-sdl')
            ->title('Schema');
    }

    /**
     * @param ResponseProceed $response
     */
    public function onResponse(ResponseProceed $response): void
    {
        $this->shareGraphQLTypes();
    }

    /**
     * @return void
     */
    private function shareGraphQLTypes(): void
    {
        $dictionary = $this->app->make(Dictionary::class);

        $types = [];

        foreach ($dictionary->all() as $type) {
            $context = $type instanceof StandardType ? 'builtin' : 'runtime';

            $types[] = [
                'Type'     => (string)$type,
                'Document' => $type->getDocument()->getFile()->exists()
                    ? \vsprintf('%s:%d', [
                        $type->getDocument()->getFile()->getPathname(),
                        $type->getDeclarationLine(),
                    ])
                    : $context,
            ];
        }

        $this->data->table('', $types);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseProceed::class => ['onResponse', -100],
        ];
    }
}
