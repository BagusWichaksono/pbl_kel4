<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluationQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question_text' => 'Seberapa mudah aplikasi ini digunakan (kemudahan navigasi dan antarmuka)?',
                'min_scale'     => 1,
                'max_scale'     => 10,
                'order'         => 1,
                'is_active'     => true,
            ],
            [
                'question_text' => 'Seberapa puas Anda dengan keseluruhan pengalaman menggunakan aplikasi ini?',
                'min_scale'     => 1,
                'max_scale'     => 10,
                'order'         => 2,
                'is_active'     => true,
            ],
            [
                'question_text' => 'Seberapa stabil aplikasi ini (tidak crash/error selama pengujian)?',
                'min_scale'     => 1,
                'max_scale'     => 10,
                'order'         => 3,
                'is_active'     => true,
            ],
            [
                'question_text' => 'Seberapa baik performa/kecepatan aplikasi ini?',
                'min_scale'     => 1,
                'max_scale'     => 10,
                'order'         => 4,
                'is_active'     => true,
            ],
            [
                'question_text' => 'Seberapa besar kemungkinan Anda merekomendasikan aplikasi ini kepada orang lain?',
                'min_scale'     => 1,
                'max_scale'     => 10,
                'order'         => 5,
                'is_active'     => true,
            ],
        ];

        foreach ($questions as $question) {
            DB::table('evaluation_questions')->insertOrIgnore($question);
        }
    }
}
