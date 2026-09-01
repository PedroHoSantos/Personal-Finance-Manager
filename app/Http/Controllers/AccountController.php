<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function __construct(private AccountService $service)
    {
    }

    public function index(): Response
    {
        $accounts = auth()->user()->accounts()->get();

        return Inertia::render('Accounts/Index', ['accounts' => $accounts]);
    }

    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $this->service->create($request->user(), $request->validated());

        return redirect()->route('accounts.index');
    }

    public function update(UpdateAccountRequest $request, Account $account): RedirectResponse
    {
        $this->service->update($account, $request->validated());

        return redirect()->route('accounts.index');
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorize('delete', $account);

        $this->service->delete($account);

        return redirect()->route('accounts.index');
    }
}