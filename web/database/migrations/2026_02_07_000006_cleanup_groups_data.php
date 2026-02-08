<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Reset all year values to NULL
        DB::table('groups')->update(['year' => null]);

        // 2. Set year correctly: only for groups whose name starts with a digit
        foreach (DB::table('groups')->get() as $group) {
            if ($group->name && preg_match('/^(\d)/', $group->name, $matches)) {
                DB::table('groups')
                    ->where('id', $group->id)
                    ->update(['year' => (int) $matches[1]]);
            }
        }

        // 3. Null out main_group_id for users pointing to groups that will be deleted
        $classGroupIds = DB::table('groups')->whereNotNull('year')->pluck('id');

        DB::table('users')
            ->whereNotNull('main_group_id')
            ->whereNotIn('main_group_id', $classGroupIds)
            ->update(['main_group_id' => null]);

        // 4. Delete all non-class groups (year is still NULL)
        DB::table('groups')->whereNull('year')->delete();
    }

    public function down(): void
    {
        // Data migration — not reversible
    }
};
