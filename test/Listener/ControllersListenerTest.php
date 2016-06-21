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

use Es\Controllers\Controllers;
use Es\Debug\Listener\ControllersListener;
use Es\Debug\ToolbarEvent;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class ControllersListenerTest extends \PHPUnit_framework_TestCase
{
    use ServicesTrait;

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new ControllersListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), ControllersListener::TEMPLATE);
        $this->assertSame($model, $listener->getModel());
    }

    public function testGetModel()
    {
        $listener = new ControllersListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), ControllersListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $controllers = new Controllers();
        $controllers->set('foo', new \stdClass());
        $services = new Services();
        $services->set('Controllers', $controllers);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new ControllersListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['controllers']));
        $items = $model['controllers'];
        $this->assertInternalType('array', $items);

        $this->assertSame(1, count($items));
        $this->assertArrayHasKey('foo', $items);
    }
}
