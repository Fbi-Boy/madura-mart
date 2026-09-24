<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['code'=>'UNT-001','name'=>'pcs','description'=>'Satuan per item','is_active'=>true],
            ['code'=>'UNT-002','name'=>'kg','description'=>'Kilogram','is_active'=>true],
            ['code'=>'UNT-003','name'=>'liter','description'=>'Liter','is_active'=>true],
            ['code'=>'UNT-004','name'=>'dus','description'=>'Satuan per dus','is_active'=>true],
            ['code'=>'UNT-005','name'=>'pack','description'=>'Satuan per kemasan','is_active'=>false],
        ] as $unit) {
            Unit::updateOrCreate(['code'=>$unit['code']], $unit);
        }
    }
}