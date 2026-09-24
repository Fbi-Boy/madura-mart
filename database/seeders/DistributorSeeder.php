<?php

namespace Database\Seeders;

use App\Models\Distributor;
use Illuminate\Database\Seeder;

class DistributorSeeder extends Seeder
{
    public function run(): void
    {
        $distributors = [
            ['code'=>'DST-001','name'=>'CV Madura Distribusi','contact_person'=>'Hendra Wijaya','phone'=>'081234567890','email'=>'hendra@maduradistribusi.test','address'=>'Jl. Trunojoyo No. 20','city'=>'Pamekasan','is_active'=>true],
            ['code'=>'DST-002','name'=>'PT Nusantara Retail Supply','contact_person'=>'Dewi Lestari','phone'=>'082345678901','email'=>'dewi@nusantarasupply.test','address'=>'Jl. Diponegoro No. 15','city'=>'Sumenep','is_active'=>true],
            ['code'=>'DST-003','name'=>'UD Sumber Niaga','contact_person'=>'Arif Rahman','phone'=>'083456789012','email'=>'arif@sumberniaga.test','address'=>'Jl. Panglima Sudirman No. 8','city'=>'Sampang','is_active'=>false],
        ];

        foreach ($distributors as $distributor) {
            Distributor::updateOrCreate(['code'=>$distributor['code']], $distributor);
        }
    }
}