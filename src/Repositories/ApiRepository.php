<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 27/09/2018
 * Time: 09:54
 */

namespace Z1lab\JsonApi\Repositories;

use Illuminate\Support\Facades\Cache;
use Z1lab\JsonApi\Traits\CacheTrait;

abstract class ApiRepository implements RepositoryInterface
{
    use CacheTrait;
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    /**
     * @var int
     */
    protected $pages;
    /**
     * @var string
     */
    protected $namespace;
    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * ApiRepository constructor.
     *
     * @param        $model
     * @param string $namespace
     */
    public function __construct($model, string $namespace)
    {
        $this->model = $model;
        $this->namespace = $namespace;
        $this->pages = config('json_api.pagination_size');

        $this->setCacheKey($namespace);
    }

    /**
     * @param array $data
     * @return mixed | $this->model
     */
    public function create(array $data)
    {
        $item = $this->model->create($data);

        $this->setCacheKey($item->id);
        $this->remember($item);

        return $item;
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

        $this->setCacheKey($id);
        $this->flush()->remember($item);

        return $item->fresh();
    }

    /**
     * @param string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $this->flush();

        return $this->find($id)->destroy($id);
    }

    /**
     * @param string $id
     * @param array  $with
     * @return mixed
     */
    public function find(string $id, array $with = [])
    {
        $this->setCacheKey($id);

        $item = Cache::tags($this->namespace)->remember($this->cacheKey, $this->cacheDefault(), function () use ($id, $with) {
            return $this->model->with($with)->find($id);
        });

        if ($this->emptyResult($item)) abort(404);

        return $item;
    }

    /**
     * @param int $items
     * @return mixed | $this->model
     */
    public function list(int $items = 0)
    {
        if ($items === 0) $items = $this->pages;

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
        $this->setCacheKey($column . str_slug($value));

        $item = Cache::tags($this->namespace)->remember($this->cacheKey, $this->cacheDefault(), function () use ($with, $column, $value) {
            return $this->model->with($with)->where($column, $value)->first();
        });

        if ($this->emptyResult($item)) abort(404);

        return $item;
    }

    /**
     * @param array $keys
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all(array $keys = [])
    {
        $slug = empty($keys) ? '' : '-' . implode('-', $keys);

        $this->setCacheKey($slug);

        $data = Cache::tags($this->namespace)->remember($this->cacheKey, $this->cacheDefault(), function () use ($keys) {
            return $this->model->all($keys);
        });

        if (!$this->emptyResult($data)) return $data;

        return [];
    }

    /**
     * @param $data
     * @return bool
     */
    private function emptyResult($data): bool
    {
        if (NULL !== $data) return FALSE;

        $this->forget();

        return TRUE;
    }

    /**
     * @param $data
     * @return $this
     */
    public function remember($data)
    {
        Cache::tags($this->namespace)->put($this->cacheKey, $data, $this->cacheDefault());

        return $this;
    }

    /**
     * Remove the regsiter from cache
     */
    public function forget()
    {
        Cache::forget($this->cacheKey);

        return $this;
    }

    /**
     * Remove all register from $this->namespace from cache
     */
    public function flush()
    {
        Cache::tags($this->namespace)->flush();

        return $this;
    }

    /**
     * @param $value
     */
    protected function setCacheKey(string $value)
    {
        ($this->cacheKey === NULL)
            ? $this->cacheKey = $value
            : $this->cacheKey .= "-$value";
    }
}
