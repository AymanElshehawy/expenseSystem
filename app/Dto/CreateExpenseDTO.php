<?php


namespace App\Dto;


use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class CreateExpenseDTO extends DataTransferObject
{
    public string $name;
    public string $date;
    public ?UploadedFile $file;
    public string $amount;
}
