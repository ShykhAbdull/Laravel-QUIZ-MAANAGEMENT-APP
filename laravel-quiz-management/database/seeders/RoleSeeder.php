<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){

// Check if roles exist, if not create them

$adminRole = Role::firstOrCreate(['name' => 'Admin']);
$managerRole = Role::firstOrCreate(['name' => 'Manager']);
$studentRole = Role::firstOrCreate(['name' => 'Student']);

// Check if permissions exist, if not create them
$addUsersPermission = Permission::firstOrCreate(['name' => 'add users']);
$viewStudentsPermission = Permission::firstOrCreate(['name' => 'view students']);
$assignQuizzesPermission = Permission::firstOrCreate(['name' => 'assign quizzes']);
$viewResultsPermission = Permission::firstOrCreate(['name' => 'view results']);

// Assign permissions to Admin role
$adminRole->syncPermissions([
    $addUsersPermission, 
    $assignQuizzesPermission, 
    $viewStudentsPermission, 
    $viewResultsPermission
]);

// Assign permissions to Manager role
$managerRole->syncPermissions([
    $assignQuizzesPermission, 
    $viewStudentsPermission
]);

// Assign permission to Student role
$studentRole->syncPermissions([
    $viewResultsPermission
]);


    }
}
