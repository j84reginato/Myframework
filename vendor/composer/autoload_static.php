<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf7c56fb9121dad829d780f91672e02f8
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Myframework\\' => 12,
        ),
        'A' => 
        array (
            'Application\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Myframework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/library/myframework',
        ),
        'Application\\' => 
        array (
            0 => __DIR__ . '/../..' . '/application/modules',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf7c56fb9121dad829d780f91672e02f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf7c56fb9121dad829d780f91672e02f8::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
