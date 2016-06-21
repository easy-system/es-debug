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

use Es\Debug\Debug;
use Es\Http\Stream;
use Es\Mvc\ViewModelInterface;
use Es\System\SystemEvent;
use Es\System\SystemTrait;
use Es\View\ViewModel;
use Es\View\ViewTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * Creates the report about debugging dump.
 */
class InjectDumpListener
{
    use SystemTrait, ViewTrait;

    /**
     * The template of View Model.
     *
     * @const string
     */
    const TEMPLATE = 'debug/dump';

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
     * Creates the report about debugging dump.
     *
     * @param \Es\System\SystemEvent $event The system event
     */
    public function __invoke(SystemEvent $event)
    {
        $system = $this->getSystem();
        if ($system->isDevMode()) {
            $result = $event->getResult(SystemEvent::FINISH);
            if ($result instanceof ResponseInterface) {
                $contentType = $result->getHeaderLine('Content-Type');
                if (0 === strpos($contentType, 'text/html')) {
                    $model = $this->getModel();
                    $view  = $this->getView();

                    $model['dumps'] = Debug::getDumpInstances();

                    $injection = $view->render($model);

                    $body   = (string) $result->getBody();
                    $html   = str_replace('</body>', $injection . '</body>', $body);
                    $stream = Stream::make($html);
                    $event->setResult(SystemEvent::FINISH, $result->withBody($stream));
                }
            }
        }
    }
}
