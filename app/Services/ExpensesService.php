<?php


namespace App\Services;


use App\Dto\CreateExpenseDTO;
use App\Enums\SystemEnums;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpensesService
{
    public function list()
    {
        $list = (new Expense())->newModelQuery();
        if (auth()->user()->hasRole(SystemEnums::UserIsEmployee)){
            $list->where('user_id', '=', auth()->id());
        }
        return $list->paginate(5);
    }

    public function create(Expense $expense, CreateExpenseDTO $createExpenseDTO): bool
    {
        DB::beginTransaction();
        $expense->name = $createExpenseDTO->name;
        $expense->date = $createExpenseDTO->date;
        $expense->amount = $createExpenseDTO->amount;
        $expense->user_id = auth()->id();
        if ($createExpenseDTO->file){
            $expense->file = $createExpenseDTO->file->store('files', 'public');
        }
        if (!$expense->save()){
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }

    /**
     * @param int $id
     * @return Expense|null
     */
    public function findByID(int $id):? Expense
    {
        $expense = Expense::where('id', '=', $id);
        if (auth()->user()->hasRole(SystemEnums::UserIsEmployee)){
            $expense->where('user_id', '=', auth()->id());
        }
        return $expense->first();
    }

    public function cancel(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense){
            return false;
        }
        return $this->changeStatus($expense, SystemEnums::StatusIsCanceled);
    }

    public function approve(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense){
            return false;
        }
        return $this->changeStatus($expense, SystemEnums::StatusIsApproved);
    }

    public function reject(int $id): bool
    {
        $expense = $this->findByID($id);
        if (!$expense){
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
