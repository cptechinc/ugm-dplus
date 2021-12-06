<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitca74aaafdb38d46af24f78169485a1ba
{
    public static $classMap = array (
        'Dplus\\Min\\Inmain\\Addm\\Addm' => __DIR__ . '/../..' . '/src/Inmain/Addm/Addm.php',
        'Dplus\\Min\\Inmain\\Addm\\Response' => __DIR__ . '/../..' . '/src/Inmain/Addm/Response.php',
        'Dplus\\Min\\Inmain\\I2i\\I2i' => __DIR__ . '/../..' . '/src/Inmain/I2i/I2i.php',
        'Dplus\\Min\\Inmain\\I2i\\Response' => __DIR__ . '/../..' . '/src/Inmain/I2i/Response.php',
        'Dplus\\Min\\Inproc\\Iarn\\Iarn' => __DIR__ . '/../..' . '/src/Inproc/Iarn.php',
        'Dplus\\Min\\Response' => __DIR__ . '/../..' . '/src/Response.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitca74aaafdb38d46af24f78169485a1ba::$classMap;

        }, null, ClassLoader::class);
    }
}