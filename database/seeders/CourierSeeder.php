<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            ['code'=>'KUR-001','name'=>'Andi Setiawan','phone'=>'081234567890','email'=>'andi@maduramart.test','address'=>'Jl. Jokotole No. 10','vehicle_type'=>'Motor','vehicle_number'=>'M 1234 AB','is_active'=>true],
            ['code'=>'KUR-002','name'=>'Fajar Maulana','phone'=>'082345678901','email'=>'fajar@maduramart.test','address'=>'Jl. Trunojoyo No. 22','vehicle_type'=>'Mobil','vehicle_number'=>'M 5678 CD','is_active'=>true],
            ['code'=>'KUR-003','name'=>'Rian Prakoso','phone'=>'083456789012','email'=>'rian@maduramart.test','address'=>'Jl. Diponegoro No. 9','vehicle_type'=>'Motor','vehicle_number'=>'M 9012 EF','is_active'=>false],
        ];

        foreach ($couriers as $courier) {
            Courier::updateOrCreate(['code'=>$courier['code']], $courier);
        }
    }
}