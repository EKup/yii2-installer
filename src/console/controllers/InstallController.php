<?php

namespace ekup\yii2\installer\console\controllers;

use ekup\yii2\installer\configurator\Configurator;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class InstallController
 */
class InstallController extends Controller
{
    public function actionRun()
    {
        $this->stdout("\nRunned controller!!\n", Console::FG_RED);

        $configurator = new Configurator([
            'actions' => [
                ['createStructure', 'param' => 'value'],
            ],
            'controller' => $this,
        ]);

        $configurator->execute();
    }
}
