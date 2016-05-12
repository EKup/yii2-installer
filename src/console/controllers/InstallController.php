<?php

namespace ekup\yii2_installer\console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class InstallController
 */
class InstallController extends Controller
{
    public function actionRun()
    {
        $this->stdout("\nRunned!!\n", Console::FG_RED);
    }
}
