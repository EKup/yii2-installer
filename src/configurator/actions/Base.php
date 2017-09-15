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
        'setWritable' => '\\ekup\\yii2\\installer\\configurator\\actions\\SetWritable',
        'setExecutable' => '\\ekup\\yii2\\installer\\configurator\\actions\\SetExecutable',
        'setCookieValidationKey' => '\\ekup\\yii2\\installer\\configurator\\actions\\SetCookieValidationKey',
        'changeDbParameters' => '\\ekup\\yii2\\installer\\configurator\\actions\\ChangeDbParameters',
        'createSymlink' => '\\ekup\\yii2\\installer\\configurator\\actions\\CreateSymlink',
    ];
    /** @var  Controller */
    public $controller;
    /** @var bool  */
    protected $_stopped = false;

    /**
     * @param array $config
     * @return static
     * @throws InvalidConfigException
     */
    public static function createAction($config)
    {
        if (!isset($config['class'])) {
            if (isset($config[0])) {
                if (isset(static::$_baseActions[$config[0]])) {
                    $config['class'] = static::$_baseActions[$config[0]];
                    unset($config[0]);
                } else {
                    throw new InvalidConfigException('Action configuration must be an array containing a "class" or type element.');
                }
            } else {
                throw new InvalidConfigException('Action configuration must be an array containing a "class" or type element.');
            }
        }

        return \Yii::createObject($config);
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

    /**
     * @param string $text
     * @param bool $highlight
     */
    public function showMessage($text, $highlight=false)
    {
        if ($this->controller instanceof \yii\console\Controller) {
            $this->controller->stdout("{$text}\n", $highlight ? Console::FG_GREEN : null);
        } else {
            echo "{$text}\n";
        }
    }

    /**
     * @param string $text
     * @param bool $default
     * @return bool
     */
    public function confirm($text, $default=false)
    {
        if ($this->controller instanceof \yii\console\Controller) {
            return $this->controller->confirm($text, $default);
        } else {
            return false;
        }
    }

    /**
     * @param string $text
     * @param array $options
     * @return mixed|string
     */
    public function prompt($text, $options = [])
    {
        if ($this->controller instanceof \yii\console\Controller) {
            return $this->controller->prompt($text, $options);
        } else {
            return isset($options['default']) ? $options['default'] : '';
        }
    }

    /**
     *
     */
    public function stop()
    {
        $this->_stopped = true;
    }

    /**
     * @return bool
     */
    public function stopped()
    {
        return $this->_stopped;
    }

    /**
     * @return string
     */
    protected function _getRootPath()
    {
        return realpath(\Yii::getAlias('@app/../'));
    }
}