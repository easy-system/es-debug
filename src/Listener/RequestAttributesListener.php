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

use Es\Debug\ToolbarEvent;
use Es\Mvc\ViewModelInterface;
use Es\Server\ServerTrait;
use Es\View\ViewModel;

/**
 * Adds the attributes of request to the debug toolbar.
 */
class RequestAttributesListener
{
    use ServerTrait;

    /**
     * The template of View Model.
     *
     * @const string
     */
    const TEMPLATE = 'debug/request-attributes';

    /**
     * The View Model.
     *
     * @var \Es\Mvc\ViewModelInterface
     */
    protected $model;

    /**
     * Sets the View Model.
     *
     * @param \Es\Mvc\ViewModelInterface $model The View Model
     */
    public function setModel(ViewModelInterface $model)
    {
        $this->model = $model->setTemplate(static::TEMPLATE);
    }

    /**
     * Gets the View Model.
     *
     * @return \Es\Mvc\ViewModelInterface The View Model
     */
    public function getModel()
    {
        if (! $this->model) {
            $this->setModel(new ViewModel());
        }

        return $this->model;
    }

    /**
     * Adds the attributes of request to the debug toolbar.
     *
     * @param \Es\Debug\ToolbarEvent $event The event of the debug toolbar
     */
    public function __invoke(ToolbarEvent $event)
    {
        $model   = $this->getModel();
        $server  = $this->getServer();
        $request = $server->getRequest();

        $model['request_attributes'] = $request->getAttributes();

        $rootModel = $event->getContext();
        $rootModel->addChild($model);
    }
}
