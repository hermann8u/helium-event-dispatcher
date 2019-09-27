<?php

namespace Helium\EventDispatcher\ListenerProvider;

trait ListenersRuntimeStorageTrait
{
    /** @var array<string, callable[]> */
    private $runtimeListenersStorage;

    /**
     * @return self
     */
    public function resetStorage(): self
    {
        $this->runtimeListenersStorage = [];

        return $this;
    }

    /**
     * @param string $eventName
     *
     * @return self
     */
    public function initInStore(string $eventName): self
    {
        $this->runtimeListenersStorage[$eventName] = [];

        return $this;
    }

    /**
     * @param string $eventName
     *
     * @return bool
     */
    public function storeHas(string $eventName): bool
    {
        return isset($this->runtimeListenersStorage[$eventName]);
    }

    /**
     * @param string $eventName
     *
     * @return iterable<callable>
     */
    public function get(string $eventName): iterable
    {
        if (!$this->storeHas($eventName)) {
            throw new \InvalidArgumentException();
        }

        return $this->runtimeListenersStorage[$eventName];
    }

    /**
     * @param string $eventName
     * @param callable ...$listeners
     *
     * @return self
     */
    public function store(string $eventName, callable ...$listeners): self
    {
        foreach ($listeners as $listener) {
            $this->runtimeListenersStorage[$eventName][] = $listener;
        }

        return $this;
    }
}
