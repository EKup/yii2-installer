<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class ChangeDbParameters
 */
class ChangeDbParameters extends Base
{
    /** @var string[] список файлов (относительные пути) */
    public $files = [];

    protected static $_dbTypes = [
        [
            'code' => 'pgsql',
            'port' => '5432',
        ],
        [
            'code' => 'mysql',
            'port' => '3306',
        ],
    ];

    /** @inheritdoc */
    public function run()
    {
        $root = $this->_getRootPath();
        $this->showMessage(\Yii::t('installer', 'Установка параметров подключения к БД'));
        $dbTypeNum = null;

        do {
            $this->showMessage(
                \Yii::t('installer', 'Выберите тип базы данных: [0-{n}, q - выход]', [
                    'n' => count(static::$_dbTypes) - 1,
                ]),
                true
            );

            foreach (static::$_dbTypes as $num => $type) {
                $this->showMessage("   {$num} - {$type['code']}");
            }

            $dbTypeNum = trim(fgets(STDIN));

            if ($dbTypeNum === 'q') {
                $this->showMessage(\Yii::t('installer', 'Отменено'));
                return null;
            }

            if (!ctype_digit($dbTypeNum) || !in_array($dbTypeNum, range(0, count(static::$_dbTypes) - 1))) {
                $dbTypeNum = null;
            }
        } while ($dbTypeNum === null);

        $dbType = static::$_dbTypes[$dbTypeNum]['code'];

        do {
            $this->showMessage(\Yii::t('installer', 'Введите адрес БД (хост):'));
            $host = trim(fgets(STDIN));
        } while (empty($host));

        $dbDefaultPort = static::$_dbTypes[$dbTypeNum]['port'];

        $this->showMessage(\Yii::t('installer', 'Введите порт БД (пустое значение - {d}):', [
            'd' => $dbDefaultPort,
        ]));

        $dbPort = trim(fgets(STDIN));

        if (strlen($dbPort) == 0) {
            $dbPort = $dbDefaultPort;
        }

        do {
            $this->showMessage(\Yii::t('installer', 'Введите название БД:'));
            $dbName = trim(fgets(STDIN));
        } while (empty($dbName));

        do {
            $this->showMessage(\Yii::t('installer', 'Введите логин пользователя БД:'));
            $login = trim(fgets(STDIN));
        } while (empty($login));

        $this->showMessage(\Yii::t('installer', 'Введите пароль пользователя БД:'));
        $password = trim(fgets(STDIN));

        $this->showMessage(\Yii::t('installer', "Установленные параметры:\n{t}\n{h}:{p}\n{n}\n{l}\n{d}", [
            't' => $dbType,
            'h' => $host,
            'p' => $dbPort,
            'n' => $dbName,
            'l' => $login,
            'd' => $password,
        ]), true);

        foreach ($this->files as $path) {
            $file = $root . '/' . $path;

            $content = file_get_contents($file);
            $content = str_replace(
                array('#DB_TYPE#', '#DB_HOST#', '#DB_PORT#', '#DB_NAME#', '#DB_LOGIN#', '#DB_PASSWORD#'),
                array($dbType, $host, $dbPort, $dbName, $login, $password),
                $content);

            file_put_contents($file, $content);
        }

        \Yii::$app->set('db', [
            'class' => 'yii\db\Connection',
            'dsn' => "{$dbType}:host={$host};port={$dbPort};dbname={$dbName}",
            'username' => $login,
            'password' => $password,
            'charset' => 'utf8',
        ]);
    }
}