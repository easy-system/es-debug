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

use Es\Debug\Listener\ServicesListener;
use Es\Debug\ToolbarEvent;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class ServicesListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new ServicesListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), ServicesListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new ServicesListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), ServicesListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $services = new Services();
        $services->set('foo', new \stdClass());
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new ServicesListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['services']));
        $items = $model['services'];
        $this->assertInternalType('array', $items);

        $this->assertSame(1, count($items));
        $this->assertArrayHasKey('foo', $items);
    }
}
