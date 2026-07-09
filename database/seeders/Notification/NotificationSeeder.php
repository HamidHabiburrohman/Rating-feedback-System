<?php

namespace Database\Seeders\Notification;

use App\Models\System\Notification;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();
        $employees = Employee::all();
        $students = Student::all();

        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                Notification::factory(3)->forAdmin($admin)->unread()->create();
                Notification::factory(2)->forAdmin($admin)->read()->create();
            }
        }

        if ($employees->isNotEmpty()) {
            foreach ($employees as $employee) {
                Notification::factory(5)->forEmployee($employee)->unread()->create();
                Notification::factory(3)->forEmployee($employee)->read()->create();
            }
        }

        if ($students->isNotEmpty()) {
            foreach ($students as $student) {
                Notification::factory(5)->forStudent($student)->unread()->create();
                Notification::factory(3)->forStudent($student)->read()->create();
            }
        }
    }
}