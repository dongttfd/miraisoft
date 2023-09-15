<?php

namespace App\Services;

use App\Exceptions\Account\AccountNotExistedException;
use App\Repositories\Contracts\AccountRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AccountService extends BaseService
{
    /**
     * @var AccountRepository;
     */
    private $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(
        AccountRepository $accountRepository,
    ) {
        parent::__construct();

        $this->accountRepository = $accountRepository;
    }

    /**
     * Store account
     *
     * @param Array $name
     * @return App\Models\Account
     */
    public function store($attributes)
    {
        $account = $this->accountRepository->create($attributes);

        $this->logger->info('[Created] Account: ' . $account->toJson());

        return $account;
    }

    /**
     * Update account
     *
     * @param int $id
     * @param array $attributes
     * @return App\Models\Account
     */
    public function update($id, $attributes)
    {
        $account = $this->accountRepository->update($id, $attributes);

        $this->logger->info('[Updated] Account: ' . $account->toJson());

        return $account;
    }


    /**
     * Get account with id
     *
     * @param int $id
     * @return App\Models\Account
     */
    public function show($id)
    {
        $account = $this->accountRepository->find($id);

        if (!$account) {
            throw new ModelNotFoundException();
        }

        return $account;
    }

    /**
     * Get account list and return that with pagination
     *
     * @param int $limit
     * @return Illuminate\Pagination\Paginator
     */
    public function paginate($limit = DEFAULT_LIMIT)
    {
        return $this->accountRepository->paginate($limit);
    }

    /**
     * Destroy account
     */
    public function destroy($id)
    {
        $deleted = $this->accountRepository->delete($id);

        $logMessage = "[Delete] Account ID: {$id}";

        $this->logger->{$deleted ? 'info' : 'warning'}($logMessage);

        return $deleted;
    }
}
