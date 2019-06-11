<?php

use Faker\Generator;

## TAG
$factory->define(\App\Entities\Tag::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->unique()->country,
    ];
});

$factory->state(\App\Entities\Tag::class, 'tag_repeat_title', [
    'name' => 'repeat_title',
]);


## Tool
$factory->define(\App\Entities\Tool::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->unique()->name,
        'link' => $faker->url,
        'description' => $faker->text,
        'tags' => new \Doctrine\Common\Collections\ArrayCollection(),
    ];
});

$factory->state(\App\Entities\Tool::class, 'tool_repeat_title', [
    'title' => 'repeat_title',
]);
