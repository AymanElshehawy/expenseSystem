<?php

namespace Tests\Feature;

use App\Dto\CreateExpenseDTO;
use App\Enums\SystemEnums;
use App\Models\Expense;
use App\Models\User;
use App\Services\ExpensesService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\DataTransferObject\DataTransferObjectError;
use Tests\TestCase;

class ExpensesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_empty_db()
    {
        $this->assertDatabaseCount('expenses', 0);
    }

    public function test_create_empty_dto()
    {
        $this->expectException(DataTransferObjectError::class);
        new CreateExpenseDTO([]);
    }

    public function test_create_valid()
    {
        $this->create_expense(SystemEnums::UserIsEmployee);
        $this->assertDatabaseCount('expenses', 1);
    }

    public function test_create_valid_with_file()
    {
        $this->create_expense(SystemEnums::UserIsEmployee, 1, true);
        $this->assertDatabaseCount('expenses', 1);
        Storage::disk('public')->assertExists(Expense::find(1)->file);
    }

    public function test_list_with_files()
    {
        $this->create_expense(SystemEnums::UserIsEmployee, 3, true);
        $this->assertDatabaseCount('expenses', 3);
        Storage::disk('public')->assertExists(Expense::find(1)->file);
    }

    public function test_list_without_files()
    {
        $this->create_expense(SystemEnums::UserIsEmployee, 3);
        $this->assertDatabaseCount('expenses', 3);
        Storage::disk('public')->assertMissing(Expense::find(1)->file);
    }

    public function test_findByID_valid()
    {
        $this->create_expense(SystemEnums::UserIsEmployee);
        $expense = (new ExpensesService())->findByID(1);
        $this->assertNotNull($expense);
        $this->assertDatabaseCount('expenses', 1);
        Storage::disk('public')->assertMissing(Expense::find(1)->file);
    }

    public function test_findByID_invalid()
    {
        $this->create_expense(SystemEnums::UserIsEmployee);
        $expense = (new ExpensesService())->findByID(2);
        $this->assertNull($expense);
        $this->assertDatabaseCount('expenses', 1);
        Storage::disk('public')->assertMissing(Expense::find(1)->file);
    }

    public function test_findByID_invalid_user()
    {
        $this->create_expense(SystemEnums::UserIsEmployee);
        $this->create_expense(SystemEnums::UserIsManager);
        $this->loginUser(SystemEnums::UserIsEmployee);
        $expense = (new ExpensesService())->findByID(2);
        $this->assertNull($expense);
        $this->assertDatabaseCount('expenses', 2);
        Storage::disk('public')->assertMissing(Expense::find(1)->file);
    }

    public function test_findByID_valid_user()
    {
        $this->create_expense(SystemEnums::UserIsEmployee);
        $this->create_expense(SystemEnums::UserIsManager);
        $this->loginUser(SystemEnums::UserIsEmployee);
        $expense = (new ExpensesService())->findByID(1);
        $this->assertNotNull($expense);
        $this->assertDatabaseCount('expenses', 2);
        Storage::disk('public')->assertMissing(Expense::find(1)->file);
    }

    private function loginUser(string $type): void
    {
        $this->actingAs(User::where('type', '=', $type)->first());
    }

    private function create_expense(string $user_type, int $count = 1, bool $has_file = false)
    {
        for ($i=0; $i<$count; $i++){
            $dto = new CreateExpenseDTO([
                'name'=> 'Test Name',
                'date'=> Carbon::now()->format('Y-m-d'),
                'amount'=>'100'
            ]);
            if ($has_file){
                $dto->file = UploadedFile::fake()->create('test', 10, 'pdf');
            }
            $this->loginUser($user_type);
            (new ExpensesService())->create(new Expense(), $dto);
        }
    }
}
