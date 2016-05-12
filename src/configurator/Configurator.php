<?php
namespace ekup\yii2\installer\configurator;
use ekup\yii2\installer\configurator\actions\Base;
use yii\base\Component;
use yii\base\Controller;

/**
 * Class Configurator
 */
class Configurator extends Component
{
    public $actions = [];
    /** @var  Controller */
    public $controller;

    public function execute()
    {
        foreach ($this->actions as $actionParams) {
            $action = $this->createAction($actionParams);
            $action->run();
        }
    }

    /**
     * @param $action
     * @return Base
     * @throws \yii\base\InvalidConfigException
     */
    protected function createAction($action)
    {
        $action['controller'] = $this->controller;

        return Base::createAction($action);
    }
}