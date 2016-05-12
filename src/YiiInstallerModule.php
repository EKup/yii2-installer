<?php

namespace ekup\yii2_installer;

use yii\base\Module;
use yii\console\Application;

class YiiInstallerModule extends Module
{
    /** @inheritdoc */
    public function init()
    {
        parent::init();

        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = '\ekup\yii2_installer\console\controllers';
        }
    }
}
