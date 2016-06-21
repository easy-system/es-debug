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
use Es\Debug\Listener\TimersListener;
use Es\Debug\ToolbarEvent;
use Es\Events\Listeners;
use Es\Http\ServerRequest;
use Es\Mvc\ViewModelInterface;
use Es\Server\Server;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class TimersListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testSetProfiler()
    {
        $listeners = new Listeners();
        $services  = new Services();
        $services->set('Listeners', $listeners);
        $this->setServices($services);

        $profiler = new ProfilerListener();
        $listener = new TimersListener();
        $listener->setProfiler($profiler);
        $this->assertSame($profiler, $listeners->get('SystemProfiler'));
    }

    public function testGetProfiler()
    {
        $profiler  = new ProfilerListener();
        $listeners = new Listeners();
        $listeners->set('SystemProfiler', $profiler);

        $services = new Services();
        $services->set('Listeners', $listeners);
        $this->setServices($services);

        $listener = new TimersListener();
        $this->assertSame($profiler, $listener->getProfiler());
    }

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new TimersListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), TimersListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new TimersListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), TimersListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $request = $this->getMock(ServerRequest::CLASS, ['getServerParam']);
        $server  = new Server($request);

        $profiler  = $this->getMock(ProfilerListener::CLASS, ['getTimers']);
        $listeners = new Listeners();
        $listeners->set('SystemProfiler', $profiler);

        $services = new Services();
        $services->set('Server', $server);
        $services->set('Listeners', $listeners);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $timers = [
            'foo' => [
                'start' => time() - 100,
                'stop'  => time(),
            ],
        ];

        $profiler
            ->expects($this->once())
            ->method('getTimers')
            ->will($this->returnValue($timers));

        $request
            ->expects($this->once())
            ->method('getServerParam')
            ->with('REQUEST_TIME_FLOAT')
            ->will($this->returnValue(time() - 360));

        $model    = new ViewModel();
        $listener = new TimersListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['timers']));
        $items = $model['timers'];
        $this->assertInternalType('array', $items);

        $this->assertSame(2, count($items));
        $this->assertArrayHasKey('foo', $items);
        $this->assertTrue($items['foo'] > 360);

        $this->assertArrayHasKey('Total', $items);
        $this->assertSame($items['Total'], $items['foo']);
    }
}
