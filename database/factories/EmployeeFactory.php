<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * Factory State
     * ● Secara default, saat membuat Factory, kita wajib meng-override method definition(), yang
     *   digunakan sebagai state awal data ketika dibuat menggunakan Factory
     * ● Selanjutnya, kita bisa membuat state lainnya, dimana state awal akan menggunakan data dari
     *   method definition()
     */
    public function definition()
    {
        return [
            "id" => "",
            "name" => "",
            "title" => "",
            "salary" => 0,
        ];
    }

    public function programmer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'Programmer',
                'salary' => 5000000
            ];
        });
    }

    public function seniorProgrammer(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'title' => 'Senior Programmer',
                'salary' => 10000000
            ];
        });
    }
}
