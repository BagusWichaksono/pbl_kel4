<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\App;
use App\Models\User;
use App\Models\ApplicationTester;

class ApplicationTesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $applications = App::all();

        $testers = User::where('role', 'tester')
            ->where('email', 'like', 'tester%@gmail.com')
            ->get();

        if ($testers->count() < 12) {
            return;
        }

        foreach ($applications as $application) {
            $selectedTesters = $testers->random(12);

            foreach ($selectedTesters as $tester) {
                ApplicationTester::create([
                    'application_id' => $application->id,
                    'tester_id' => $tester->id,
                    'status' => 'active',
                ]);
            }
        }
    }
}