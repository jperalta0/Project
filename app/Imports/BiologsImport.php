<?php

namespace App\Imports;

use App\Employee_biolog;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class BiologsImport implements ToModel, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        $check = \App\Employee::where('id',$row[1])->first();
        if($check != NULL){
            $empid = $check->id;
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]);
            Employee_biolog::firstOrCreate([
                'employee_id'      =>      $empid,
                'date'             =>      $date,
                'status'           =>      $row[3],
            ]);
        }

       
    }

    public function batchSize(): int
    {
        return 200;
    }
    
    public function chunkSize(): int
    {
        return 200;
    }
}
