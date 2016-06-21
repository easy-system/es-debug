<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Test\Listener;

use Es\Debug\Listener\ProfilerListener;
use Es\Events\Event;
use ReflectionProperty;

class ProfilerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testCaptureStartEventWithoutName()
    {
        $event    = new Event();
        $listener = new ProfilerListener();
        $listener->captureStart($event);

        $reflection = new ReflectionProperty($listener, 'timers');
        $reflection->setAccessible(true);
        $timers = $reflection->getValue($listener);

        $this->assertSame(1, count($timers));
        $this->assertArrayHasKey(1, $timers);
        $this->assertTrue(isset($timers[1]['start']));
        $this->assertTrue(isset($timers[1]['stop']));
        $this->assertSame($timers[1]['start'], $timers[1]['stop']);
    }

    public function testCaptureStartEventWithName()
    {
        $event    = new Event('foo');
        $listener = new ProfilerListener();
        $listener->captureStart($event);

        $reflection = new ReflectionProperty($listener, 'timers');
        $reflection->setAccessible(true);
        $timers = $reflection->getValue($listener);

        $this->assertSame(1, count($timers));
        $this->assertArrayHasKey('foo', $timers);
        $this->assertTrue(isset($timers['foo']['start']));
        $this->assertTrue(isset($timers['foo']['stop']));
        $this->assertSame($timers['foo']['start'], $timers['foo']['stop']);
    }

    public function testCaptureStopEventWithoutName()
    {
        $event    = new Event();
        $listener = new ProfilerListener();
        $listener->captureStart($event);
        sleep(1);
        $listener->captureStop($event);

        $reflection = new ReflectionProperty($listener, 'timers');
        $reflection->setAccessible(true);
        $timers = $reflection->getValue($listener);

        $this->assertSame(1, count($timers));
        $this->assertArrayHasKey(1, $timers);
        $this->assertTrue(isset($timers[1]['start']));
        $this->assertTrue(isset($timers[1]['stop']));
        $this->assertTrue($timers[1]['start'] < $timers[1]['stop']);
    }

    public function testCaptureStopEventWithName()
    {
        $event    = new Event('foo');
        $listener = new ProfilerListener();
        $listener->captureStart($event);
        sleep(1);
        $listener->captureStop($event);

        $reflection = new ReflectionProperty($listener, 'timers');
        $reflection->setAccessible(true);
        $timers = $reflection->getValue($listener);

        $this->assertSame(1, count($timers));
        $this->assertArrayHasKey('foo', $timers);
        $this->assertTrue(isset($timers['foo']['start']));
        $this->assertTrue(isset($timers['foo']['stop']));
        $this->assertTrue($timers['foo']['start'] < $timers['foo']['stop']);
    }

    public function testGetTimers()
    {
        $event    = new Event();
        $listener = new ProfilerListener();
        $listener->captureStart($event);
        $listener->captureStop($event);

        $reflection = new ReflectionProperty($listener, 'timers');
        $reflection->setAccessible(true);
        $timers = $reflection->getValue($listener);

        $this->assertSame($timers, $listener->getTimers());
    }
}
