<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if offices already exist, if not, insert them
        if (DB::table('offices')->count() == 0) {
            DB::table('offices')->insert([
                [
                    'id' => 1,
                    'name' => 'Ateneo Center for Culture & the Arts',
                    'code' => 'ACCA'
                ],
                [
                    'id' => 2,
                    'name' => 'Ateneo Center for Environment & Sustainability',
                    'code' => 'ACES'
                ],
                [
                    'id' => 3,
                    'name' => 'Ateneo Center for Leadership & Governance',
                    'code' => 'ACLG'
                ],
                [
                    'id' => 4,
                    'name' => 'Ateneo Peace Center',
                    'code' => 'APC'
                ],
                [
                    'id' => 5,
                    'name' => 'Center for Community Extension Services',
                    'code' => 'CCES'
                ],
                [
                    'id' => 6,
                    'name' => 'Ateneo Learning and Teaching Excellence Center',
                    'code' => 'ALTEC'
                ]
            ]);
        }
        
        // Create the Unit Director user
        $office = \App\Models\Office::where('code', 'ACCA')->first();
        
        if ($office) {
            \App\Models\User::create([
                'full_name' => 'SDU Director',
                'email' => 'director@sdu.edu.ph',
                'password_hash' => \Illuminate\Support\Facades\Hash::make('sdo123'),
                'role' => 'unit_director',
                'office_code' => $office->code,
                'is_approved' => true,
            ]);
        }
    }
}