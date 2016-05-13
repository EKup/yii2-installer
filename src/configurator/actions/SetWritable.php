<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class SetWritable
 */
class SetWritable extends Base
{
    /** @var string[] список файлов (относительные пути) */
    public $files = [];

    /** @inheritdoc */
    public function run()
    {
        $root = $this->_getRootPath();
        $this->showMessage(\Yii::t('installer', 'Выполнение chmod'));

        foreach ($this->files as $file) {
            $this->showMessage(\Yii::t('installer', '      chmod 0777 {f}', [
                'f' => $file,
            ]));

            @chmod("{$root}/{$file}", 0777);
        }
    }
}