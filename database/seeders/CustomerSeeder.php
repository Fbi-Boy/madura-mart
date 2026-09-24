<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['code'=>'CUS-001','name'=>'Ahmad Fauzi','phone'=>'081234567890','email'=>'ahmad@example.com','address'=>'Jl. Jokotole No. 12','city'=>'Pamekasan','is_active'=>true],
            ['code'=>'CUS-002','name'=>'Nur Aini','phone'=>'082345678901','email'=>'nuraini@example.com','address'=>'Jl. Trunojoyo No. 18','city'=>'Sumenep','is_active'=>true],
            ['code'=>'CUS-003','name'=>'Rizky Pratama','phone'=>'083456789012','email'=>'rizky@example.com','address'=>'Jl. Diponegoro No. 7','city'=>'Sampang','is_active'=>false],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(['code'=>$customer['code']], $customer);
        }
    }
}