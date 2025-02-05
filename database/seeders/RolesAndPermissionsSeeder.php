<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{


  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Reset cached roles and permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $permissions = $this->GetAllPermissions();

    Permission::insert($permissions);

    // create roles and assign permissions
    $superAdmin = Role::create(['name' => 'Super Admin']);
    $superAdmin->givePermissionTo(Permission::all());

    $admin =Role::create(['name' => 'Admin'])
      ->givePermissionTo(Permission::all());
    
    $sale_role = Role::create(['name' => 'Principal']);
    $doctor_role = Role::create(['name' => 'Vice Principal']);
    $staff_member_role = Role::create(['name' => 'Staff Member']);
    $patient_role = Role::create(['name' => 'Faculty Member']);
    $patient_role = Role::create(['name' => 'Accountant']);

  }
	private function GetAllPermissions()
	{
		$model_permissions = [
			'role' => ['view', 'view-list', 'create', 'edit', 'destroy', 'assign', 'remove', 'revoke', 'view-deleted', 'restore-deleted', 'assign-admin', 'assign-super-admin', 'export', 'print', 'copy',],
			'permission' => ['view', 'view-list', 'create', 'edit', 'destroy', 'assign', 'remove', 'revoke', 'view-deleted', 'restore-deleted', 'export', 'print', 'copy',],
			'user' => ['view', 'view-list', 'add', 'edit', 'destroy', 'view-deleted', 'restore-deleted', 'export', 'print', 'copy',],
			'institute' => ['view', 'view-list', 'create', 'edit', 'destroy', 'view-deleted', 'restore-deleted', 'export', 'print', 'copy',],
			'subscription' => ['view', 'view-list', 'create', 'edit', 'destroy', 'view-deleted', 'restore-deleted', 'export', 'print', 'copy'],

			'settings' => ['view', 'edit',],
			'report' => ['view', 'generate',],
			'backup' => ['view', 'take', 'generate', 'destroy',]
		];

		$prefix_permissions = [
			'copy' => [
				'roles',
				'permissions',
				'users',
				'user-profile',
				'institutes',
				'subscriptions',
				'reports',
				'sales',
				'purchases',
				'access-control'
			],
			'toggle' => ['institutes', 'subscriptions',],

			'view' => [
				'roles',
				'permissions',
				'users',
				'user-profile',
				'institutes',
				'subscriptions',
				'reports',
				'sales',
				'purchases',
				'access-control'
			],
			'get' => ['subscription-expiry-alert', 'expiry-alert'],
			'export-as' => ['pdf', 'csv', 'excel', 'json', 'xml'],
			'backup' => ['app', 'db',],
			'change' => ['user-password']
		];


		$all_permissions = [];
		$all_db_permissions = [];

		foreach ($model_permissions as $model => $permissions) {
			foreach ($permissions as $permission) {
				$all_permissions[] = "{$permission}-{$model}";
			}
		}

		foreach ($prefix_permissions as $permission => $models) {
			foreach ($models as $model) {
				$all_permissions[] = "{$permission}-{$model}";
			}
		}

		foreach ($all_permissions as $permission) {
			$all_db_permissions[] = ['name' => "{$permission}", 'guard_name' => 'web', 'created_at' => now()];
		}
		return $all_db_permissions;
	}

}
  
