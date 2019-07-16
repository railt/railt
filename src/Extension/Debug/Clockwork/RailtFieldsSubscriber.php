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
use Railt\Dumper\TypeDumper;
use Railt\Foundation\Event\Http\RequestReceived;
use Railt\Foundation\Event\Resolver\FieldResolve;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class RailtFieldsSubscriber
 */
class RailtFieldsSubscriber implements EventSubscriberInterface
{
    /**
     * @var Clockwork
     */
    private $clockwork;

    /**
     * RailtFieldsSubscriber constructor.
     *
     * @param Clockwork $clockwork
     */
    public function __construct(Clockwork $clockwork)
    {
        $this->clockwork = $clockwork;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RequestReceived::class => ['onRequest', 100],
            FieldResolve::class    => ['onResolve', -1],
        ];
    }

    /**
     * @param FieldResolve $event
     */
    public function onResolve(FieldResolve $event): void
    {
        $request = $event->getRequest();
        $input = $event->getInput();

        /** @var UserData $context */
        $context = $this->clockwork->userData('railt-fields-' . $request->getId());

        if ($event->hasResult()) {
            $context->table('Field :' . $input->getPath(), [
                ['Name' => 'Type', 'Value' => $input->getTypeName()],
                ['Name' => 'Field', 'Value' => $input->getField()],
                ['Name' => 'Alias', 'Value' => $input->getAlias()],
                ['Name' => 'Arguments', 'Value' => $input->all()],
                ['Name' => 'Prefer Types', 'Value' => $input->getPreferTypes()],
                ['Name' => 'Result', 'Value' => TypeDumper::render($event->getResult())],
                ['Name' => 'Relations', 'Value' => $input->getRelations()],
            ]);
        }
    }

    /**
     * @param RequestReceived $event
     */
    public function onRequest(RequestReceived $event): void
    {
        $request = $event->getRequest();

        /** @var UserData $context */
        $context = $this->clockwork
            ->userData('railt-fields-' . $request->getId())
            ->title('Fields');
    }
}
