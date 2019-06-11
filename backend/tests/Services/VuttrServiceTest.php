<?php


use App\Entities\Tool;
use App\Services\VuttrServiceInterface;
use App\Entities\Tag;
use Faker\Generator;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class VuttrServiceTest extends TestCase
{

    /**
     * @var VuttrServiceInterface
     */
    private $vuttrService;

    public function setUp(): void
    {
        parent::setUp();
        $this->vuttrService = app()->make(VuttrServiceInterface::class);
    }

    /**
     * Test the number of tools
     *
     * @return void
     */
    public function testListToolsCount()
    {
        $tools = $this->vuttrService->tools();
        $this->assertEquals(
            0, count($tools)
        );

        $tools = entity(Tool::class, 2)->create();
        $tools = $this->vuttrService->tools();
        $this->assertEquals(
            2, count($tools)
        );
    }

    public function testListToolsUnique()
    {
        $exception = false;
        try {
            entity(Tool::class, 2)->states('tool_repeat_title')->create();
        } catch (UniqueConstraintViolationException $e) {
            $exception = true;
        }
        $this->assertTrue($exception);
    }

    public function testListToolsByTags()
    {
        list($tools, $tags) = $this->_addTool();

        $toolsByTag = $this->vuttrService->tools($tags[0]);
        $this->assertCount(1, $toolsByTag);
        $this->assertEquals($tools[0], $toolsByTag[0]);

        $toolsByTag = $this->vuttrService->tools($tags[1]);
        $this->assertCount(2, $toolsByTag);
        $this->assertEquals($tools[0], $toolsByTag[0]);
        $this->assertEquals($tools[1], $toolsByTag[1]);

        $toolsByTag = $this->vuttrService->tools($tags[2]);
        $this->assertCount(1, $toolsByTag);
        $this->assertEquals($tools[1], $toolsByTag[0]);
    }

    public function testRepeatTag()
    {
        $faker = $this->app->make(Generator::class);

        $title = $faker->unique()->country;
        $link = $faker->url;
        $description = $faker->text;
        $tags = $faker->country;
        $tags = [$tags, $tags];

        $tool = $this->vuttrService->addTool($title, $link, $description, $tags);

        $this->assertCount(1, $tool->getTags());
    }

    private function _addTool()
    {
        $faker = $this->app->make(Generator::class);

        $tools = [];
        $tags = [
            $faker->unique()->name,
            $faker->unique()->name,
            $faker->unique()->name,
        ];


        $title = $faker->unique()->country;
        $link = $faker->url;
        $description = $faker->text;
        $sliceTags = array_slice($tags, 0, 2);
        $tools[] = $this->vuttrService->addTool($title, $link, $description, $sliceTags);


        $title = $faker->unique()->country;
        $link = $faker->url;
        $description = $faker->text;
        $sliceTags = array_slice($tags, 1, 2);
        $tools[] = $this->vuttrService->addTool($title, $link, $description, $sliceTags);

        return [$tools, $tags];
    }

    public function testListTagsCount()
    {
        entity(Tag::class, 3)->create();
        $tags = EntityManager::getRepository(Tag::class)->findAll();
        $this->assertCount(3, $tags);
    }

    public function testListTagsUnique()
    {
        $exception = false;
        try {
            entity(Tag::class, 3)->states('tag_repeat_title')->create();
        } catch (UniqueConstraintViolationException $e) {
            $exception = true;
        }
        $this->assertTrue($exception);
    }

}
