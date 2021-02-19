<?php

namespace App\Http\Controllers;

use App\Dto\CreateExpenseDTO;
use App\Http\Requests\CreateExpenseRequest;
use App\Models\Expense;
use App\Services\ExpensesService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * @var ExpensesService
     */
    private ExpensesService $ExpensesService;

    public function __construct()
    {
        $this->ExpensesService = new ExpensesService();
    }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $expenses = $this->ExpensesService->list();
        return view('expenses.index')->with(['expenses' => $expenses]);
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('expenses.form');
    }

    /**
     * @param CreateExpenseRequest $request
     * @return RedirectResponse
     */
    public function store(CreateExpenseRequest $request)
    {
        try {
            $isDone = $this->ExpensesService->create(new Expense(), new CreateExpenseDTO($request->only(['name', 'amount', 'date', 'file'])));
            if ($isDone) {
                return redirect()->route('expenses.index');
            }
            return redirect()->route('expenses.create')->withErrors(['Something went wrong while create new expenses']);
        } catch (\Exception $exception) {
            return redirect()->route('expenses.create')->withErrors(['Something went wrong while create new expenses']);
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function show($id)
    {
        if (auth()->user()->cannot('see')) {
            abort(403);
        }
        $expense = $this->ExpensesService->findByID($id);
        if (!$expense) {
            return redirect()->route('expenses.index')->withErrors(['Invalid expense']);
        }
        return view('expenses.show')->with(['expense' => $expense]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancel(int $id)
    {
        if (auth()->user()->cannot('cancel')) {
            abort(403);
        }
        if (!$this->ExpensesService->cancel($id)) {
            return redirect()->route('expenses.index')->withErrors(['Something went wrong while cancel expenses']);
        }
        return redirect()->route('expenses.index');
    }

    public function approve(int $id)
    {
        if (auth()->user()->cannot('approve')) {
            abort(403);
        }
        if (!$this->ExpensesService->approve($id)) {
            return redirect()->route('expenses.index')->withErrors(['Something went wrong while approve expenses']);
        }
        return redirect()->route('expenses.index');
    }

    public function reject(int $id)
    {
        if (auth()->user()->cannot('reject')) {
            abort(403);
        }
        if (!$this->ExpensesService->reject($id)) {
            return redirect()->route('expenses.index')->withErrors(['Something went wrong while reject expenses']);
        }
        return redirect()->route('expenses.index');
    }
}
