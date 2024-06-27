<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    /**
     * Factory
     * ● Saat kita membuat object Model, biasanya kita harus ubah tiap atribut satu satu secara manual
     * ● Laravel Eloquent memiliki fitur bernama Factory, ini sebenarnya adalah implementasi dari Design
     *   Patterns bernama Factory Patterns
     * ● Dimana, kita membuat class Factory yang digunakan untuk membuat object
     * ● Dengan begitu, jika kita membuat object yang hampir sama, kita bisa menggunakan Factory
     * ● https://refactoring.guru/design-patterns/factory-method
     *
     * Contoh Kasus
     * ● Misal kita akan membuat model Employee, dimana Employee memiliki title dan salary yang selalu
     *   sama untuk title yang sama
     * ● Untuk mempermudah, kita bisa menggunakan Factory ketika membuat object Employee
     *
     * Membuat Factory
     * ● Nama Factory secara default adalah nama Model + Factory
     * ● Jika tidak menggunakan format yang sesuai, secara default Factory tidak bisa ditemukan
     * ● Selain itu, di Model harus menggunakan trait HasFactory untuk memberitahu bahwa Model ini
     *   memiliki Factory
     * ● Untuk membuat class Factory, kita tidak perlu melakukannya secara manual, cukup gunakan
     *   perintah :
     *   php artisan make:factory NamaFactory
     *
     * Factory State
     * ● Secara default, saat membuat Factory, kita wajib meng-override method definition(), yang
     *   digunakan sebagai state awal data ketika dibuat menggunakan Factory
     * ● Selanjutnya, kita bisa membuat state lainnya, dimana state awal akan menggunakan data dari
     *   method definition()
     */

    public function testFactory()
    {

        $employee1 = Employee::factory()->make();
        $employee1->id = '1';
        $employee1->name = 'Employee 1';
        $employee1->title = 'Magang';
        $employee1->salary = 3500000;

        // sql: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
        $employee1->save();
        self::assertNotNull(Employee::where('id', '1')->first());
        $json =$employee1->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);


        $employee2 = Employee::factory()->programmer()->make(); // programmer() method yang sudah di set di EmployeeFactory
        $employee2->id = '2';
        $employee2->name = 'Employee 2';

        // sql: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
        $employee2->save();
        self::assertNotNull(Employee::where('id', '2')->first());
        $json =$employee2->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);


        // sql: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
        $employee3 = Employee::factory()->seniorProgrammer()->create([
            'id' => '3',
            'name' => 'Employee 3'
        ]); // seniorProgrammer() method yang sudah di set di EmployeeFactory
        self::assertNotNull($employee3);
        self::assertNotNull(Employee::where('id', '3')->first());
        $json =$employee3->toJson(JSON_PRETTY_PRINT); // toJson(optionas) // konversi model ke bentuk JSON
        Log::info($json);

        /**
         * result:
         * [2024-06-27 10:28:28] testing.INFO: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
         * [2024-06-27 10:28:28] testing.INFO: select * from `employees` where `id` = ? limit 1
         * [2024-06-27 10:28:28] testing.INFO: {
         * "id": "1",
         * "name": "Employee 1",
         * "title": "Magang",
         * "salary": 3500000,
         * "updated_at": "2024-06-27T10:28:28.000000Z",
         * "created_at": "2024-06-27T10:28:28.000000Z"
         * }
         * [2024-06-27 10:28:28] testing.INFO: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
         * [2024-06-27 10:28:28] testing.INFO: select * from `employees` where `id` = ? limit 1
         * [2024-06-27 10:28:28] testing.INFO: {
         * "id": "2",
         * "name": "Employee 2",
         * "title": "Programmer",
         * "salary": 5000000,
         * "updated_at": "2024-06-27T10:28:28.000000Z",
         * "created_at": "2024-06-27T10:28:28.000000Z"
         * }
         * [2024-06-27 10:28:28] testing.INFO: insert into `employees` (`id`, `name`, `title`, `salary`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?)
         * [2024-06-27 10:28:28] testing.INFO: select * from `employees` where `id` = ? limit 1
         * [2024-06-27 10:28:28] testing.INFO: {
         * "id": "3",
         * "name": "Employee 3",
         * "title": "Senior Programmer",
         * "salary": 10000000,
         * "updated_at": "2024-06-27T10:28:28.000000Z",
         * "created_at": "2024-06-27T10:28:28.000000Z"
         * }
 */
    }

}
