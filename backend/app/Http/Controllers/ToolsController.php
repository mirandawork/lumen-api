<?php

namespace App\Http\Controllers;


use App\Services\VuttrServiceInterface;
use Illuminate\Http\Request;

use App\Entities\Tool;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use stdClass;


class ToolsController extends Controller
{

    /**
     * @var VuttrServiceInterface
     */
    private $vuttrService;

    /**
     * @param VuttrServiceInterface $vuttrService
     */
    public function __construct(VuttrServiceInterface $vuttrService)
    {
        $this->vuttrService = $vuttrService;
    }

    /**
     * @OA\Get(
     *     path="/v1/tools",
     *     summary="List all tools",
     *     description="Create a new tool and tags if not exists.<br> Tools with the repeated title are not accepted. ",
     *     operationId="list",
     *     @OA\Response(
     *       response="200",
     *       description="Success",
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Property(
     *           type="array",
     *           @OA\Items(ref="#/components/schemas/tool")
     *         )
     *       )
     *     )
     *  )
     *
     * @param Request $request
     * @return Collection
     */
    public function list(Request $request): Collection
    {
        return collect($this->vuttrService->tools($request->input('tag', null)));
    }

    /**
     * @OA\Get(
     *     path="/v1/tools/{id}",
     *     summary="Get a tool by id",
     *     description="Get a tool by id.",
     *     operationId="get",
     *     @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       description="ID from the tool",
     *       @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         minimum=1
     *       )
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Success",
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Property(
     *           ref="#/components/schemas/tool"
     *         )
     *       )
     *     ),
     *     @OA\Response(
     *       response="404",
     *       description="Tool not found"
     *     )
     *  )
     *
     * @param int $id
     * @return Tool|null
     */
    public function get(int $id): Response
    {
        $tool = $this->vuttrService->getTool($id);
        if (empty($tool)) {
            return response('{}', 404, ['Content-Type' => 'application/json']);
        }
        return response($tool, 200);
    }

    /**
     * @OA\Post(
     *     path="/v1/tools",
     *     summary="Create a new tool",
     *     description="Create a new tool and tags if not exists.",
     *     operationId="create",
     *     @OA\RequestBody(
     *       description="Create tool object",
     *       required=true,
     *       @OA\JsonContent(ref="#/components/schemas/tool"),
     *     ),
     *     @OA\Response(
     *       response="201",
     *       description="Created",
     *       @OA\JsonContent(ref="#/components/schemas/tool")
     *     ),
     *     @OA\Response(
     *       response="422",
     *       description="The request is well formed but disabled to be followed due to semantic errors.",
     *       @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Property(
     *           type="object",
     *           @OA\Property(
     *             property="<field>",
     *             type="array",
     *             @OA\Items(type="string")
     *           )
     *         )
     *       )
     *     )
     *  )
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function create(Request $request): Response
    {
        $this->validate($request, [
            'title' => 'required|unique:\App\Entities\Tool,title',
            'link' => 'required',
            'description' => 'required',
            'tags' => 'required',
        ]);

        $data = $request->json();
        $tool = $this->vuttrService->addTool($data->get('title'), $data->get('link'), $data->get('description'),
            $data->get('tags'));

        return response($tool, 201);
    }

    /**
     * @OA\Delete(
     *     path="/v1/tools/{id}",
     *     summary="Delete a tool by id",
     *     operationId="delete",
     *     @OA\Parameter(
     *       name="id",
     *       in="path",
     *       required=true,
     *       description="ID from the tool",
     *       @OA\Schema(
     *         type="integer",
     *         format="int64",
     *         minimum=1
     *       )
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="OK"
     *     ),
     *     @OA\Response(
     *       response="422",
     *       description="The request is well formed but disabled to be followed due to semantic errors.",
     *       @OA\MediaType(
     *         mediaType="application/json",
     *       )
     *     )
     *  )
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        $statusCode = 422;
        if ($this->vuttrService->deleteTool($id)) {
            $statusCode = 200;
        }
        return response('{}', $statusCode, ['Content-Type' => 'application/json']);
    }
}
