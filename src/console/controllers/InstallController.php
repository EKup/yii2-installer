<?php

namespace ekup\yii2\installer\console\controllers;

use ekup\yii2\installer\configurator\Configurator;
use yii\console\Controller;

/**
 * Class InstallController
 */
class InstallController extends Controller
{
    public function actionIndex()
    {
        $configurator = new Configurator(['controller' => $this]);
        $configurator->loadConfig(realpath(\Yii::getAlias('@app/../') . '/common/config/installer/install.php'));

        $configurator->execute();
    }
}
