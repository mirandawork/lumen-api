<?php


use App\Services\VuttrServiceInterface;
use App\Entities\{Tool, Tag};
use Illuminate\Container\Container;
use Illuminate\Http\{Response, Request};
use Faker\Generator;

class ApiV1Test extends TestCase
{

    /**
     * @var VuttrServiceInterface
     */
    private $vuttrService;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->singleton(VuttrServiceInterface::class, function (Container $app) {
            return new VuttrServiceMock();
        });


        $this->vuttrService = $this->app->make(VuttrServiceInterface::class);
    }

    public function testTools(): void
    {
        $this->get('/v1/tools');

        $response = new Response(collect($this->vuttrService->tools), 200);
        $this->assertJson(
            $response->getContent(), $this->response->getContent()
        );
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertResponseOk();
    }

    public function testToolsEmpty(): void
    {
        $this->vuttrService->tools = [];
        $response = new Response(collect($this->vuttrService->tools), 200);

        $this->get('/v1/tools');
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            $response->getContent(), $this->response->getContent()
        );
        $this->assertResponseOk();
    }

    public function testToolsCreate(): void
    {
        $faker = $this->app->make(Generator::class);
        $data = [
            'title' => $faker->country,
            'link' => $faker->url,
            'description' => $faker->text,
            'tags' => [
                $faker->unique()->name,
                $faker->unique()->name,
            ]
        ];
        $this->json('POST', '/v1/tools', $data);

        $data['id'] = null;
        $response = new Response($data, 201);
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            $response->getContent(), $this->response->getContent()
        );
        $this->assertResponseStatus($response->getStatusCode());
    }

    public function testToolsGet(): void
    {
        $this->get('/v1/tools/1');

        $response = new Response(collect($this->vuttrService->tools[1]), 200);
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            $response->getContent(), $this->response->getContent()
        );
        $this->assertResponseOk();
    }

    public function testToolsGetEmpty(): void
    {
        $this->get('/v1/tools/3');
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            '{}', $this->response->getContent()
        );
        $this->assertResponseStatus(404);
    }

    public function testToolsDelete(): void
    {
        $this->delete('/v1/tools/30');
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            '{}', $this->response->getContent()
        );
        $this->assertResponseOk();
    }

    public function testToolsDeleteIdNotExists(): void
    {
        $this->delete('/v1/tools/1');
        $this->assertEquals(
            'application/json', $this->response->headers->get('Content-type')
        );
        $this->assertJson(
            '{}', $this->response->getContent()
        );
        $this->assertResponseStatus(422);
    }
}

class VuttrServiceMock implements VuttrServiceInterface
{
    /**
     * @var Tool[]
     */
    public $tools = [];

    public function __construct()
    {
        $this->tools[] = entity(Tool::class)->make();
        $this->tools[] = entity(Tool::class)->make();
    }

    public function tools(string $filterTag = null): array
    {
        return $this->tools;
    }

    public function getTool(int $id): ?Tool
    {
        if (empty($this->tools[$id])) {
            return null;
        }
        return $this->tools[$id];
    }

    public function addTool(string $title, string $link, string $description, array $tags): Tool
    {
        $tool = new Tool($title, $link, $description);
        $tool->setTags([new Tag($tags[0]), new Tag($tags[1])]);
        return $tool;
    }

    public function deleteTool(int $id): bool
    {
        return $id == 30;
    }
}
