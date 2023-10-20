<?php

namespace App\Core\Event\Task;

use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * class TaskRunRequestedEvent.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class TaskRunRequestedEvent extends Event
{
    public const RUN_REQUEST_EVENT = 'task_event.run_request';

    public function __construct(
        protected string $task,
        protected InputBag $parameters,
        protected BufferedOutput $output
    ) {
    }

    public function getTask(): string
    {
        return $this->task;
    }

    public function getParameters(): ParameterBagInterface
    {
        return $this->parameters;
    }

    public function getOutput(): BufferedOutput
    {
        return $this->output;
    }
}
