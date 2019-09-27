<?php

declare(strict_types=1);

namespace Helium\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * This ListenerProvider decorate an other one in order to register sub listener providers to handle an event given by its name.
 * It delegates its responsibilities to the sub listeners.
 */
final class DelegatorListenerProvider implements ListenerProviderInterface
{
    use ListenersRuntimeStorageTrait;

    /** @var ListenerProviderInterface */
    private $baseListenerProvider;

    /** @var array */
    private $subListenerProvidersMap;

    /**
     * DelegatorListenerProvider constructor.
     *
     * @param ListenerProviderInterface $baseListenerProvider
     * @param array $subListenerProvidersMap
     */
    public function __construct(ListenerProviderInterface $baseListenerProvider, array $subListenerProvidersMap)
    {
        $this->baseListenerProvider = $baseListenerProvider;
        $this->subListenerProvidersMap = $subListenerProvidersMap;
    }

    /**
     * {@inheritDoc}
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventName = get_class($event);

        if (!isset($this->cachedListeners[$eventName])) {
            $subListenerProvider = $this->subListenerProvidersMap[$eventName] ?? null;
            if ($subListenerProvider && $subListenerProvider instanceof ListenerProviderInterface) {
                $listeners = $subListenerProvider->getListenersForEvent($event);
                $this->store($eventName, ...$listeners);

                return $listeners;
            }

            $this->store($eventName, ...$this->baseListenerProvider->getListenersForEvent($event));
        }

        return $this->get($eventName);
    }
}
