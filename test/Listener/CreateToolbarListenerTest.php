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

use Es\Debug\Listener\CreateToolbarListener;
use Es\Debug\ToolbarEvent;
use Es\Events\Events;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\System;
use Es\System\SystemEvent;
use Es\View\ViewModel;

class CreateToolbarListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function setUp()
    {
        require_once 'SystemTestHelper.php';

        SystemTestHelper::resetSystem();
    }

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new CreateToolbarListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), CreateToolbarListener::TEMPLATE);
        $this->assertSame($model, $listener->getModel());
    }

    public function testGetModel()
    {
        $listener = new CreateToolbarListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), CreateToolbarListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $system   = System::init([], true);
        $events   = $this->getMock(Events::CLASS, ['trigger']);
        $services = new Services();
        $services->set('System', $system);
        $services->set('Events', $events);
        $this->setServices($services);

        $events
            ->expects($this->once())
            ->method('trigger')
            ->with($this->isInstanceOf(ToolbarEvent::CLASS));

        $listener = new CreateToolbarListener();
        $listener(new SystemEvent());
    }
}
