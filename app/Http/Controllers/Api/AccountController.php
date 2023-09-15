<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AccountRequest;
use App\Http\Resources\Account\AccountResource;
use App\Http\Resources\Account\AccountCollection;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends ApiController
{
    /**
     * @var AccountService;
     */
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Display a listing of the account.
     *
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->responseJsonSuccess(
            $this->accountService->paginate($request->limit ?? DEFAULT_LIMIT),
            AccountCollection::class
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountRequest $request)
    {
        return $this->responseJsonSuccess(
            $this->accountService->store($request->validated()),
            AccountResource::class
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->responseJsonSuccess(
            $this->accountService->show($id),
            AccountResource::class
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param App\Http\Requests\AccountRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AccountRequest $request, $id)
    {
        return $this->responseJsonSuccess(
            $this->accountService->update($id, $request->validated()),
            AccountResource::class
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return $this->responseJsonSuccess([
            'deleted' => (boolean) ($this->accountService->destroy($id))
        ]);
    }
}
