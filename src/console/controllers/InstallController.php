<?php

namespace ekup\yii2\installer\console\controllers;

use ekup\yii2\installer\configurator\Configurator;
use yii\console\Controller;

/**
 * Class InstallController
 */
class InstallController extends Controller
{
    public function actionRun()
    {
        $configurator = new Configurator([
            'actions' => [
                ['createStructure', 'envDescription' => [
                    'dev' => \Yii::t('installer', 'Сервер разработки/тестирования'),
                    'prod' => \Yii::t('installer', 'Боевой сервер'),
                ]],
                ['setWritable', 'files' => [
                    'backend/runtime',
                    'backend/web/assets',
                    'frontend/runtime',
                    'frontend/web/assets',
                    'console/runtime',
                ]],
                ['setExecutable', 'files' => [
                    'yii',
                    'tests/codeception/bin/yii',
                ]],
                ['setCookieValidationKey', 'files' => [
                    'backend/config/main-local.php',
                    'frontend/config/main-local.php',
                ]],
                ['changeDbParameters', 'files' => [
                    '/common/config/main-local.php',
                ]],
            ],
            'controller' => $this,
        ]);

        $configurator->execute();
    }
}
