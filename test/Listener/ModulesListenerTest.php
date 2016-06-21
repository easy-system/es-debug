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

use Es\Debug\Listener\ModulesListener;
use Es\Debug\ToolbarEvent;
use Es\Modules\AbstractModule;
use Es\Modules\Modules;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class ModulesListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new ModulesListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), ModulesListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new ModulesListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), ModulesListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $modules = new Modules();
        $module  = $this->getMock(AbstractModule::CLASS);
        $modules->set('Foo', $module);
        $services = new Services();
        $services->set('Modules', $modules);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new ModulesListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['modules']));
        $items = $model['modules'];
        $this->assertInternalType('array', $items);

        $this->assertSame(1, count($items));
        $this->assertArrayHasKey('Foo', $items);
    }
}
