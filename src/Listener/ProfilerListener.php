<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Listener;

use Es\Events\EventInterface;

/**
 * Listens of events and records the time of their execution.
 */
class ProfilerListener
{
    /**
     * The array of event names and time of their execution.
     *
     * @var array
     */
    protected $timers = [];

    /**
     * The numeric index of current timer.
     *
     * @var int
     */
    protected $index = 0;

    /**
     * Gets timers.
     *
     * @return array
     */
    public function getTimers()
    {
        return $this->timers;
    }

    /**
     * Fixes the event start time.
     *
     * @param \Es\Events\EventInterface $event The event
     */
    public function captureStart(EventInterface $event)
    {
        ++$this->index;

        $time = microtime(true);

        $eventName = $event->getName();
        if ($eventName) {
            $this->timers[$eventName] = ['start' => $time, 'stop' => $time];

            return;
        }
        $this->timers[$this->index] = ['start' => $time, 'stop' => $time];
    }

    /**
     * Fixes the event stop time.
     *
     * @param \Es\Events\EventInterface $event The event
     */
    public function captureStop(EventInterface $event)
    {
        $time = microtime(true);

        $eventName = $event->getName();
        if ($eventName && isset($this->timers[$eventName])) {
            $this->timers[$eventName]['stop'] = $time;

            return;
        }
        $this->timers[$this->index]['stop'] = $time;
    }
}
