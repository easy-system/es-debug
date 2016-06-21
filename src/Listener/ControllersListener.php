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

use Es\Controllers\ControllersTrait;
use Es\Debug\ToolbarEvent;
use Es\Mvc\ViewModelInterface;
use Es\View\ViewModel;

/**
 * Adds controllers description to the debug toolbar.
 */
class ControllersListener
{
    use ControllersTrait;

    /**
     * The template of View Model.
     *
     * @const string
     */
    const TEMPLATE = 'debug/controllers';

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
     * Adds controllers description to the debug toolbar.
     *
     * @param \Es\Debug\ToolbarEvent $event The event of the debug toolbar
     */
    public function __invoke(ToolbarEvent $event)
    {
        $controllers = $this->getControllers();
        $model       = $this->getModel();

        $items = [];
        foreach ($controllers->getInstances() as $name => $item) {
            $items[$name] = get_class($item);
        }
        $model['controllers'] = $items;

        $rootModel = $event->getContext();
        $rootModel->addChild($model);
    }
}
