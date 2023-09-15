<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

interface BaseRepository
{
    /**
     * Find by id
     *
     * @param int $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function find($id);

    /**
     * Get all
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection;

    /**
     * Get pagination
     *
     * @param int $itemPerPage
     * @return Illuminate\Pagination\Paginator;
     */
    public function paginate($itemPerPage);

    /**
     * Create record
     *
     * @param array $data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create($data): Model;

    /**
     * Update record
     *
     * @param int $params
     * @param Array $data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data);

    /**
     * Delete record
     *
     * @param int $id
     */
    public function delete($id);
}
