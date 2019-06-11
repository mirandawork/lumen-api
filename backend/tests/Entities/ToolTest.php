<?php


use LaravelDoctrine\Migrations\Testing\DatabaseMigrations;
use App\Entities\{Tool, Tag};
use Faker\Generator;

class ToolTest extends TestCase
{



    public function testAddTag()
    {
        $tool = entity(Tool::class)->make();
        $tag1 = entity(Tag::class)->make();
        $tag2 = entity(Tag::class)->make();

        $tool->setTags([$tag1]);
        $tool->setTags([$tag1, $tag2]);

        $this->assertCount(2, $tool->getTags());
        $this->assertEquals($tag1, $tool->getTags()[0]);
        $this->assertEquals($tag2, $tool->getTags()[1]);
    }

    public function testAddTagRepeat()
    {
        $tool = entity(Tool::class)->make();
        $tag1 = entity(Tag::class)->make();
        $tool->addTag($tag1);
        $tool->addTag($tag1);

        $this->assertCount(1, $tool->getTags());
        $this->assertEquals($tag1, $tool->getTags()[0]);
    }

    public function testToArray()
    {
        $faker = $this->app->make(Generator::class);

        $title = $faker->unique()->country;
        $link = $faker->url;
        $description = $faker->text;
        $tags = [new Tag($faker->unique()->country), new Tag($faker->unique()->country)];

        $tool = new Tool($title, $link, $description);
        $tool->setTags($tags);
        EntityManager::persist($tool);
        EntityManager::flush();


        $this->assertEquals([
            'id' => 1,
            'title' => $title,
            'link' => $link,
            'description' => $description,
            'tags' => [$tags[0]->getName(), $tags[1]->getName()],
        ], $tool->toArray());
    }
}
