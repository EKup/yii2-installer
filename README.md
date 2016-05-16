# yii2-installer

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Инсталлатор проекта на Yii2, вынесенный в отдельный композер-пакет.
Изначально разрабатывается для HKS, но может использоваться на любом Yii2 проекте.

## Установка

Через композер

``` bash
$ composer require ekup/yii2-installer
```

## Использование

Настройка консольного приложения проекта
``` php
'modules'    => [
    'installer'   => [
        'class' => '\ekup\yii2\installer\YiiInstallerModule',
    ],
],

'i18n' => [
        'translations' => [
            'installer' => [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'ru-RU',
                'basePath'       => '@vendor/ekup/yii2-unstaller/messages',
                'fileMap'        => [
                    'installer' => 'installer.php',
                ],
            ],
        ],
    ],
```

В папку /common/config/installer необходимо добавить файл install.php с настройками установщика:

``` php
return [
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
        [
            'class' => \main\configurator\actions\CreateUser::className(),
            'users' => [
                [
                    'email' => 'admin@admin.com',
                    'password' => '123456',
                    'role' => 'admin',
                ],
            ],
        ],
    ],
];
```

Запуск установщика:

``` php
./yii installer/install
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email ekup73@gmail.com instead of using the issue tracker.

## Credits

- [ekup][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ekup/yii2-installer.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ekup/yii2-installer/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/ekup/yii2-installer.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/ekup/yii2-installer.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ekup/yii2-installer.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ekup/yii2-installer
[link-travis]: https://travis-ci.org/ekup/yii2-installer
[link-scrutinizer]: https://scrutinizer-ci.com/g/ekup/yii2-installer/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/ekup/yii2-installer
[link-downloads]: https://packagist.org/packages/ekup/yii2-installer
[link-author]: https://github.com/ekup
[link-contributors]: ../../contributors
