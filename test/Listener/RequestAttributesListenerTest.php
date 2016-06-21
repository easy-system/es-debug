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

use Es\Debug\Listener\RequestAttributesListener;
use Es\Debug\ToolbarEvent;
use Es\Http\ServerRequest;
use Es\Mvc\ViewModelInterface;
use Es\Server\Server;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\View\ViewModel;

class RequestAttributesListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new RequestAttributesListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), RequestAttributesListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new RequestAttributesListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), RequestAttributesListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $attributes = [
            'foo' => 'bar',
            'bat' => 'baz',
        ];
        $request  = new ServerRequest(null, null, null, null, $attributes);
        $server   = new Server($request);
        $services = new Services();
        $services->set('Server', $server);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new RequestAttributesListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['request_attributes']));
        $this->assertSame($attributes, $model['request_attributes']);
    }
}
