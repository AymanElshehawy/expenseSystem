<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Services\APIExpensesService;

class APIExpensesController extends Controller
{
    /**
     * @var APIExpensesService
     */
    private APIExpensesService $APIExpensesService;

    public function __construct()
    {
        $this->APIExpensesService = new APIExpensesService();
    }

    public function index()
    {
        return ExpenseResource::collection($this->APIExpensesService->list());
    }

    public function show(int $id)
    {
        return new ExpenseResource($this->APIExpensesService->findByID($id));
    }

    public function cancel(int $id)
    {
        if (!$this->APIExpensesService->cancel($id)) {
            return response()->json(['message'=>'Something went wrong while cancel expenses'], 400);
        }
        return response()->json(['message'=>'Success!']);
    }

    public function approve(int $id)
    {
        if (!$this->APIExpensesService->approve($id)) {
            return response()->json(['message'=>'Something went wrong while approve expenses'], 400);
        }
        return response()->json(['message'=>'Success!']);
    }

    public function reject(int $id)
    {
        if (!$this->APIExpensesService->reject($id)) {
            return response()->json(['message'=>'Something went wrong while reject expenses'], 400);
        }
        return response()->json(['message'=>'Success!']);
    }
}
