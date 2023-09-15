<?php

namespace App\Repositories\Eloquent;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepository;

class AccountRepositoryEloquent extends BaseRepositoryEloquent implements AccountRepository
{
    /**
     * Defined Eloquent Model Class Name
     *
     * @return string
     */
    protected function model()
    {
        return Account::class;
    }

    /**
     * Delete record
     *
     * @param int $id
     */
    public function delete($registerId)
    {
        return $this->model->where('registerID', $registerId)->delete();
    }
}
