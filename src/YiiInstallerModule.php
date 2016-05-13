<?php

namespace ekup\yii2\installer;

use yii\base\Module;
use yii\console\Application;

class YiiInstallerModule extends Module
{
    /** @inheritdoc */
    public function init()
    {
        parent::init();

        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = '\ekup\yii2\installer\console\controllers';
        }
    }

    /** @inheritdoc */
    public function getControllerPath()
    {
        if (\Yii::$app instanceof Application) {
            return \Yii::getAlias('@vendor/ekup/yii2-installer/src/console/controllers');
        } else {
            return \Yii::getAlias('@vendor/ekup/yii2-installer/src/controllers');
        }
    }
}
