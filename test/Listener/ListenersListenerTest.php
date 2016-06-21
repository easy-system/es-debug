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

use Es\Debug\Listener\ListenersListener;
use Es\Debug\ToolbarEvent;
use Es\Events\Listeners;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class ListenersListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new ListenersListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), ListenersListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new ListenersListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), ListenersListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $listeners = new Listeners();
        $listeners->set('foo', new \stdClass());
        $services = new Services();
        $services->set('Listeners', $listeners);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new ListenersListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['listeners']));
        $items = $model['listeners'];
        $this->assertInternalType('array', $items);

        $this->assertSame(1, count($items));
        $this->assertArrayHasKey('foo', $items);
    }
}
