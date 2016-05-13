<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class SetCookieValidationKey
 */
class SetCookieValidationKey extends Base
{
    /** @var string[] список файлов (относительные пути) */
    public $files = [];

    /** @inheritdoc */
    public function run()
    {
        $root = $this->_getRootPath();
        $this->showMessage(\Yii::t('installer', 'Генерация ключа проверки куков'));

        foreach ($this->files as $file) {
            $this->showMessage(\Yii::t('installer', '   генерация в {f}', [
                'f' => $file,
            ]));

            $filePath = $root . '/' . $file;

            if (file_exists($filePath)) {
                $length = 32;
                $bytes = openssl_random_pseudo_bytes($length);
                $key = strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
                $content = preg_replace('/(("|\')cookieValidationKey("|\')\s*=>\s*)(""|\'\')/', "\\1'$key'", file_get_contents($file));
                file_put_contents($filePath, $content);
            } else {
                $this->showError(\Yii::t('installer', '   Файл {f} не найден, пропуск', [
                    'f' => $file,
                ]));
            }
        }
    }
}