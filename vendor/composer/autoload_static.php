<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba6ccca45462ff7c31ff0df98a0ac9c3
{
    public static $prefixLengthsPsr4 = array (
        'j' => 
        array (
            'j84Reginato\\MyFramework\\' => 24,
        ),
        'S' => 
        array (
            'Symfony\\Component\\Finder\\' => 25,
        ),
        'G' => 
        array (
            'Gregwar\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'j84Reginato\\MyFramework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
            1 => __DIR__ . '/../..' . '/test',
        ),
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Gregwar\\' => 
        array (
            0 => __DIR__ . '/..' . '/gregwar/captcha/src/Gregwar',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitba6ccca45462ff7c31ff0df98a0ac9c3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitba6ccca45462ff7c31ff0df98a0ac9c3::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}