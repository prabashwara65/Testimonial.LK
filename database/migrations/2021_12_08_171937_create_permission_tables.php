<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTables extends Migration
{
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teams = config('permission.teams');

        if (empty($tableNames)) {
            throw new \Exception(
                'Error: config/permission.php not loaded.'
            );
        }

        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception(
                'Error: team_foreign_key not configured.'
            );
        }

        /*
         * Permissions
         */
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            $table->unique(['name', 'guard_name']);
        });

        /*
         * Roles
         */
        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');

            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger(
                    $columnNames['team_foreign_key']
                )->nullable();

                $table->index(
                    $columnNames['team_foreign_key'],
                    'roles_team_foreign_key_index'
                );
            }

            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();

            if ($teams || config('permission.testing')) {
                $table->unique([
                    $columnNames['team_foreign_key'],
                    'name',
                    'guard_name'
                ]);
            } else {
                $table->unique([
                    'name',
                    'guard_name'
                ]);
            }
        });

        /*
         * Model Has Permissions
         */
        Schema::create(
            $tableNames['model_has_permissions'],
            function (Blueprint $table) use (
                $tableNames,
                $columnNames,
                $teams
            ) {
                $permissionColumn = 'permission_id';

                $table->unsignedBigInteger($permissionColumn);

                $table->string('model_type');

                $table->unsignedBigInteger(
                    $columnNames['model_morph_key']
                );

                $table->index(
                    [
                        $columnNames['model_morph_key'],
                        'model_type'
                    ],
                    'model_has_permissions_model_id_model_type_index'
                );

                $table->foreign($permissionColumn)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                if ($teams) {
                    $table->unsignedBigInteger(
                        $columnNames['team_foreign_key']
                    );

                    $table->index(
                        $columnNames['team_foreign_key'],
                        'model_has_permissions_team_foreign_key_index'
                    );

                    $table->primary([
                        $columnNames['team_foreign_key'],
                        $permissionColumn,
                        $columnNames['model_morph_key'],
                        'model_type'
                    ]);
                } else {
                    $table->primary([
                        $permissionColumn,
                        $columnNames['model_morph_key'],
                        'model_type'
                    ]);
                }
            }
        );

        /*
         * Model Has Roles
         */
        Schema::create(
            $tableNames['model_has_roles'],
            function (Blueprint $table) use (
                $tableNames,
                $columnNames,
                $teams
            ) {
                $roleColumn = 'role_id';

                $table->unsignedBigInteger($roleColumn);

                $table->string('model_type');

                $table->unsignedBigInteger(
                    $columnNames['model_morph_key']
                );

                $table->index(
                    [
                        $columnNames['model_morph_key'],
                        'model_type'
                    ],
                    'model_has_roles_model_id_model_type_index'
                );

                $table->foreign($roleColumn)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                if ($teams) {
                    $table->unsignedBigInteger(
                        $columnNames['team_foreign_key']
                    );

                    $table->index(
                        $columnNames['team_foreign_key'],
                        'model_has_roles_team_foreign_key_index'
                    );

                    $table->primary([
                        $columnNames['team_foreign_key'],
                        $roleColumn,
                        $columnNames['model_morph_key'],
                        'model_type'
                    ]);
                } else {
                    $table->primary([
                        $roleColumn,
                        $columnNames['model_morph_key'],
                        'model_type'
                    ]);
                }
            }
        );

        /*
         * Role Has Permissions
         */
        Schema::create(
            $tableNames['role_has_permissions'],
            function (Blueprint $table) use ($tableNames) {
                $permissionColumn = 'permission_id';
                $roleColumn = 'role_id';

                $table->unsignedBigInteger($permissionColumn);
                $table->unsignedBigInteger($roleColumn);

                $table->foreign($permissionColumn)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');

                $table->foreign($roleColumn)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');

                $table->primary([
                    $permissionColumn,
                    $roleColumn
                ]);
            }
        );

        /*
         * Clear permission cache
         */
        app('cache')
            ->store(
                config('permission.cache.store') != 'default'
                    ? config('permission.cache.store')
                    : null
            )
            ->forget(
                config('permission.cache.key')
            );
    }

    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception(
                'Error: config/permission.php not found.'
            );
        }

        Schema::dropIfExists(
            $tableNames['role_has_permissions']
        );

        Schema::dropIfExists(
            $tableNames['model_has_roles']
        );

        Schema::dropIfExists(
            $tableNames['model_has_permissions']
        );

        Schema::dropIfExists(
            $tableNames['roles']
        );

        Schema::dropIfExists(
            $tableNames['permissions']
        );
    }
}