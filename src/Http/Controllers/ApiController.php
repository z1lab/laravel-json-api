<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 26/09/2018
 * Time: 19:27
 */

namespace Z1lab\JsonApi\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

abstract class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Set the base repository
     *
     * @var \Z1lab\JsonApi\Repositories\ApiRepository
     */
    protected $repository;
    /**
     * Set the resource base ClassName
     *
     * @var
     */
    protected $resource;
    /**
     * Set the actions will be cached in http
     *
     * @var array
     */
    protected $cacheable = ['index', 'show'];

    /**
     * ApiController constructor.
     *
     * @param        $repository
     * @param string $resource
     */
    public function __construct($repository, string $resource)
    {
        if (env('APP_ENV') === 'production') $this->middleware('ttl')->only($this->cacheable);
        $this->repository = $repository;
        $this->resource = $resource;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        return $this->collectResource($this->repository->list());
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    public function show(string $id)
    {
        return $this->makeResource($this->repository->find($id));
    }


    /**
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $this->repository->destroy($id);

        return response()->json(NULL, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param $obj
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    protected function makeResource($obj)
    {
        return api_resource($this->resource)->make($obj);
    }

    /**
     * @param $collection
     * @return \Illuminate\Http\Resources\Json\Resource
     */
    protected function collectResource($collection)
    {
        return api_resource($this->resource)->collection($collection);
    }
}
