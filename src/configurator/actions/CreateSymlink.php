<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class CreateSymlink
 */
class CreateSymlink extends Base
{
    /** @var string[] // list of symlinks to be created. Keys are symlinks, and values are the targets. */
    public $links = [];

    /** @inheritdoc */
    public function run()
    {
        $root = $this->_getRootPath();
        $this->showMessage(\Yii::t('installer', 'Создание символьных ссылок'));

        foreach ($this->links as $link => $target) {
            $link = trim($link, '/');
            $target = trim($target, '/');

            $this->showMessage(\Yii::t('installer', '      ln -s ./{f1} ./{f2}', [
                'f1' => $target,
                'f2' => $link,
            ]));

            @symlink ($root . '/' . $target , $root . '/' . $link);
        }
    }
}