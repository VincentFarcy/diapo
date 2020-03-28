<?php

namespace App\DataFixtures\Faker;

use \Faker\Provider\Base as BaseProvider;

class DiapoProvider extends BaseProvider {

    protected static $tags = [
        'Jardin',
        'Graine',
        'MS',
    ];

    public static function tagTitle()
    {
        return static::randomElement(static::$tags);
    }

    protected static $images = [
        'photo1.jpeg',
        'photo2.jpeg',
        'photo3.jpeg',
        'photo4.jpeg',
        'photo5.jpeg',
        'photo6.jpeg',
        'photo7.jpeg',
        'photo8.jpeg',
        'photo9.jpeg',
        'photo10.jpeg',
    ];

    public static function imageSrc()
    {
        return static::randomElement(static::$images);
    }
}
