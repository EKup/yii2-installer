<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class CreateStructure
 */
class CreateStructure extends Base
{
    /** @var  string папка с конфигами */
    public $envFolder;
    /** @var array описание конфигов */
    public $envDescription = [];

    /** @inheritdoc */
    public function run()
    {
        $this->showMessage(\Yii::t('installer', 'Запуск инициализации проекта'));

        if (count($this->envDescription) == 0) {
            $this->showError(\Yii::t('installer', 'Отсутствует конфиг окружения, шаг пропушен'));
            return;
        }

        $env = $this->_askEnv();

        if ($env === null) {
            return;
        }

        $this->_copyEnv('default', false);

        if (!$this->stopped()) {
            $this->_copyEnv($env);
        }
    }

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        if ($this->envFolder === null) {
            $this->envFolder = realpath(__DIR__ . '/../../environments');
        }

        if (!is_array($this->envDescription)) {
            $this->envDescription = [];
        }
    }

    /**
     * @return null|string
     */
    protected function _askEnv()
    {
        $answer = null;
        $environments = array_keys($this->envDescription);

        while ($answer === null || !array_key_exists($answer, $this->envDescription)) {
            $this->showMessage(
                \Yii::t('installer', 'Выберите окружение, которым хотите инициализировать проект [0-{n}, q - выход]', [
                    'n' => count($environments) - 1,
                ]),
                true
            );

            foreach ($environments as $num => $env) {
                $this->showMessage("[{$num}] - {$env} ({$this->envDescription[$env]})");
            }

            $answer = trim(fgets(STDIN));

            if ($answer === 'q') {
                $this->showMessage(\Yii::t('installer', 'Инициализация проекта отменена'));
                return null;
            }

            if (!ctype_digit($answer) || !in_array($answer, range(0, count($environments) - 1))) {
                $answer = null;
            } else {
                $answer = $environments[$answer];
            }
        }

        return $answer;
    }

    /**
     * @param string $env
     * @param bool $showError
     * @return bool
     */
    protected function _copyEnv($env, $showError=true)
    {
        $folderPath = $this->envFolder . '/' . $env;

        if (!file_exists($folderPath) || !is_dir($folderPath)) {
            if ($showError) {
                $this->showError(\Yii::t('installer', 'Отсутствует папка "{f}"', ['f' => $env]));
                return false;
            }

            return true;
        }

        $files = $this->_getFileList($folderPath);
        $all = false;

        foreach ($files as $file) {
            if (!$this->_copyFile("{$folderPath}/{$file}", $file, $all)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $root
     * @param string $basePath
     * @return string[]
     */
    protected function _getFileList($root, $basePath = '')
    {
        $files = [];
        $handle = opendir($root);

        while (($path = readdir($handle)) !== false) {
            if (in_array($path, ['.git', '.svn', '.', '..'], true)) {
                continue;
            }

            $fullPath = "$root/$path";
            $relativePath = $basePath === '' ? $path : "$basePath/$path";

            if (is_dir($fullPath)) {
                $files = array_merge($files, $this->_getFileList($fullPath, $relativePath));
            } else {
                $files[] = $relativePath;
            }
        }

        closedir($handle);
        return $files;
    }

    /**
     * @param string $source
     * @param string $target
     * @param bool $all
     * @return bool
     */
    protected function _copyFile($source, $target, &$all)
    {
        $root = $this->_getRootPath();

        if (!is_file($source)) {
            $this->showMessage(\Yii::t('installer', "       пропущен {t} ({s] не найден)", [
                't' => $target,
                's' => $source,
            ]));

            return true;
        }

        if (is_file($root . '/' . $target)) {
            if (file_get_contents($source) === file_get_contents($root . '/' . $target)) {
                $this->showMessage(\Yii::t('installer', "  не изменен {t}", [
                    't' => $target,
                ]));

                return true;
            }

            if ($all) {
                $this->showMessage(\Yii::t('installer', "  перезапись {t}", [
                    't' => $target,
                ]));
            } else {
                $this->showMessage(\Yii::t('installer', "      уже существует {t}", [
                    't' => $target,
                ]));

                $this->showMessage(\Yii::t('installer', "            ...перезаписать? [Yes|No|All|Quit]"));
                $answer = trim(fgets(STDIN));

                if (!strncasecmp($answer, 'q', 1)) {
                    $this->stop();

                    return false;
                } else {
                    if (!strncasecmp($answer, 'y', 1)) {
                        $this->showMessage(\Yii::t('installer', "  перезапись {t}", [
                            't' => $target,
                        ]));
                    } else {
                        if (!strncasecmp($answer, 'a', 1)) {
                            $this->showMessage(\Yii::t('installer', "  перезапись {t}", [
                                't' => $target,
                            ]));
                            $all = true;
                        } else {
                            $this->showMessage(\Yii::t('installer', "  пропуск {t}", [
                                't' => $target,
                            ]));

                            return true;
                        }
                    }
                }
            }

            file_put_contents($root . '/' . $target, file_get_contents($source));
            return true;
        }

        $this->showMessage(\Yii::t('installer', "  копирование {t}", [
            't' => $target,
        ]));

        @mkdir(dirname($root . '/' . $target), 0777, true);
        file_put_contents($root . '/' . $target, file_get_contents($source));

        return true;
    }
}