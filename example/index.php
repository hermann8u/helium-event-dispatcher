<?php

use Helium\EventDispatcher\StoppableEventTrait;
use Psr\EventDispatcher\StoppableEventInterface;

require '../vendor/autoload.php';

class FirstEvent
{
}

class SecondEvent extends FirstEvent
{
}

class ThirdEvent implements StoppableEventInterface
{
    use StoppableEventTrait;
}

class InvokableListener
{
    public function __invoke(FirstEvent $event)
    {
        dump(sprintf('-- Call with invokable %s --', get_class($this)));
    }
}

function getIterator()
{
    for ($i = 0; $i < 10; $i++) {
        if ($i % 3 === 0) {
            yield new InvokableListener();
        } elseif ($i % 3 === 1) {
            yield function (SecondEvent $event) {
                dump('-- Call with SecondEvent --');
            };
        } elseif ($i % 3 === 2) {
            yield function (object $event) {
            };
        }
    }
}

$arrayListeners = [
    function (FirstEvent $event) {
        dump('-- Call with FirstEvent --');
    },
    function () {
    },
    function (SecondEvent $event) {
        dump('-- Call with SecondEvent --');
    },
    new InvokableListener(),
];

$provider = new \Helium\EventDispatcher\ListenerProvider\DefaultListenerProvider(getIterator());

$eventDispatcher = new \Helium\EventDispatcher\EventDispatcher($provider);

$eventDispatcher->dispatch(new ThirdEvent());
$eventDispatcher->dispatch(new FirstEvent());
$eventDispatcher->dispatch(new SecondEvent());
