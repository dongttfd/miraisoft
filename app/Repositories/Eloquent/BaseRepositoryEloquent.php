<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepository;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

abstract class BaseRepositoryEloquent implements BaseRepository
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Defined Eloquent Model Class Name
     *
     * @return string
     */
    abstract protected function model();

    /**
     * Make eloquent model
     *
     * @return Illuminate\Database\Eloquent\Model;
     */
    public function makeModel()
    {
        if ($this->model) {
            return $this->model;
        }

        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Find by id
     *
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get all
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get pagination
     *
     * @param int $itemPerPage
     * @return Illuminate\Pagination\Paginator
     */
    public function paginate($itemPerPage = DEFAULT_LIMIT)
    {
        return $this->model->paginate($itemPerPage);
    }

    /**
     * Create record
     *
     * @param array $data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     *
     * @param int $id
     * @param Array $data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data)
    {
        $account = $this->find($id);
        $account->update($data);

        return $account->fresh();
    }

    /**
     * Delete record
     *
     * @param int $id
     */
    public function delete($id)
    {
        return $this->model->whereId($id)->delete();
    }
}
