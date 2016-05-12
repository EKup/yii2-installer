<?php
namespace ekup\yii2\installer\configurator\actions;

use yii\base\Component;
use yii\base\Controller;
use yii\base\InvalidConfigException;
use yii\helpers\Console;

/**
 * Class Base
 */
abstract class Base extends Component implements ActionInterface
{
    protected static $_baseActions = [
        'createStructure' => '\\ekup\\yii2\\installer\\configurator\\actions\\CreateStructure',
    ];
    /** @var  Controller */
    public $controller;

    /**
     * @param array $config
     * @return static
     * @throws InvalidConfigException
     */
    public static function createAction($config)
    {
        if (isset($config['class'])) {
            $className = $config['class'];
            unset($config['class']);
        } elseif (isset($config[0])) {
            if (isset(static::$_baseActions[$config[0]])) {
                $className = static::$_baseActions[$config[0]];
                unset($config[0]);
            } else {
                throw new InvalidConfigException('Action configuration must be an array containing a "class" or type element.');
            }
        } else {
            throw new InvalidConfigException('Action configuration must be an array containing a "class" or type element.');
        }

        return \Yii::createObject($className, $config);
    }

    /**
     * @param string $text
     */
    public function showError($text)
    {
        if ($this->controller instanceof \yii\console\Controller) {
            $this->controller->stdout("{$text}\n", Console::FG_RED);
        } else {
            echo "{$text}\n";
        }
    }
}