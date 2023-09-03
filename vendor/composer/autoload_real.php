<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitd8aa6ec348c78938f5897e8291dcba5f
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitd8aa6ec348c78938f5897e8291dcba5f', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitd8aa6ec348c78938f5897e8291dcba5f', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitd8aa6ec348c78938f5897e8291dcba5f::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
