<?php
/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Api VUTTR (Very Useful Tools to Remember)",
 *   contact={
 *     "email": "andre@miranda.work"
 *   }
 * )
 */


$router->get('/', function() {
    return redirect('/v1/tools');
});


$router->get('/v1/tools', 'ToolsController@list');
$router->get('/v1/tools/{id}', 'ToolsController@get');
$router->post('/v1/tools', 'ToolsController@create');
$router->delete('/v1/tools/{id}', 'ToolsController@delete');

