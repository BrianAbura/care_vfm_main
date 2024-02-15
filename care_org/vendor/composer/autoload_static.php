<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbc5d2d19efe0b43120cf8a8cae93ada9
{
    public static $prefixesPsr0 = array (
        'B' => 
        array (
            'Bramus' => 
            array (
                0 => __DIR__ . '/..' . '/bramus/router/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitbc5d2d19efe0b43120cf8a8cae93ada9::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitbc5d2d19efe0b43120cf8a8cae93ada9::$classMap;

        }, null, ClassLoader::class);
    }
}