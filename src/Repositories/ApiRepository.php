<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 27/09/2018
 * Time: 09:54
 */

namespace Z1lab\JsonApi\Repositories;

use Z1lab\JsonApi\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;

abstract class ApiRepository implements RepositoryInterface
{
    use CacheTrait;
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    /**
     * @var string
     */
    protected $cacheKey;
    /**
     * @var int
     */
    protected $pages;

    public function __construct($model, string $cacheKey)
    {
        $this->model = $model;
        $this->cacheKey = $cacheKey;
        $this->pages = config('json_api.pagination_size');
    }

    /**
     * @param array $data
     * @return mixed | $this->model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param string $id
     * @param array  $data
     * @return mixed | $this->model
     */
    public function update(array $data, string $id)
    {
        $item = $this->find($id);
        $item->update($data);

        return $item->fresh();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        return $this->find($id)->destroy($id);
    }

    /**
     * @param string $id
     * @param array  $with
     * @return mixed
     */
    public function find(string $id, array $with = [])
    {
        $item = Cache::remember("{$this->cacheKey}-{$id}", $this->cacheDefault(), function () use ($id, $with) {
            return $this->model->with($with)->find($id);
        });

        if (NULL === $item) abort(404);

        return $item;
    }

    /**
     * @param int $items
     * @return mixed | $this->model
     */
    public function list(int $items = 0)
    {
        if($items === 0) $items = $this->pages;

        return $this->model->paginate($items);
    }

    /**
     * @param string $column
     * @param        $value
     * @param array  $with
     * @return mixed
     */
    public function findWhere(string $column, $value, array $with = [])
    {
        $item = $this->model->with($with)->where($column, $value)->first();

        if (NULL === $item) abort(404);

        return $item;
    }
}
