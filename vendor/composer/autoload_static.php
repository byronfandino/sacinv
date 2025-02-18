<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4a1f0d8083a2a14201ef3cfc6600aa75
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Model\\' => 6,
            'MVC\\' => 4,
        ),
        'G' => 
        array (
            'Global\\' => 7,
        ),
        'C' => 
        array (
            'Controllers\\' => 12,
            'Classes\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/models',
        ),
        'MVC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
        'Global\\' => 
        array (
            0 => __DIR__ . '/../..' . '/global',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/controllers',
        ),
        'Classes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4a1f0d8083a2a14201ef3cfc6600aa75::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4a1f0d8083a2a14201ef3cfc6600aa75::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4a1f0d8083a2a14201ef3cfc6600aa75::$classMap;

        }, null, ClassLoader::class);
    }
}
