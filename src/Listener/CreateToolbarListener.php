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
use Es\Events\EventsTrait;
use Es\Mvc\ViewModelInterface;
use Es\System\SystemEvent;
use Es\View\ViewModel;
use Es\System\SystemTrait;

/**
 * Creates the debug toolbar.
 */
class CreateToolbarListener
{
    use EventsTrait, SystemTrait;

    /**
     * The template of View Model.
     *
     * @const string
     */
    const TEMPLATE = 'debug/toolbar';

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
     * Creates the debug toolbar.
     *
     * @param \Es\System\SystemEvent $event The system event
     */
    public function __invoke(SystemEvent $event)
    {
        $system = $this->getSystem();
        if ($system->isDevMode()) {
            $model  = $this->getModel();
            $events = $this->getEvents();
            $events->trigger(new ToolbarEvent($model));
        }
    }
}
