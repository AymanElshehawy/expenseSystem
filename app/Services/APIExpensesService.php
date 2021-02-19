<?php


namespace App\Services;


use App\Enums\SystemEnums;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class APIExpensesService
{
    public function list()
    {
        return Expense::paginate(10);
    }

    /**
     * @param int $id
     * @return Expense|null
     */
    public function findByID(int $id):? Expense
    {
        return Expense::find($id);
    }

    public function cancel(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense || $expense->status == SystemEnums::StatusIsCanceled){
            return false;
        }
        return $this->changeStatus($expense, SystemEnums::StatusIsCanceled);
    }

    public function approve(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense || $expense->status == SystemEnums::StatusIsApproved){
            return false;
        }
        return $this->changeStatus($expense, SystemEnums::StatusIsApproved);
    }

    public function reject(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense || $expense->status == SystemEnums::StatusIsRejected){
            return false;
        }
        return $this->changeStatus($expense, SystemEnums::StatusIsRejected);
    }

    private function changeStatus(Expense $expense, string $status)
    {
        DB::beginTransaction();
        $expense->status = $status;
        if (!$expense->save()){
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}
