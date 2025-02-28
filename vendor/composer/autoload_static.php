<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6b1e52406d0e69cabc43872710fa6fc2
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Pc\\Datn\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Pc\\Datn\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6b1e52406d0e69cabc43872710fa6fc2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6b1e52406d0e69cabc43872710fa6fc2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6b1e52406d0e69cabc43872710fa6fc2::$classMap;

        }, null, ClassLoader::class);
    }
}
