<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Auto-populate year on groups from first character of name
        foreach (DB::table('groups')->get() as $group) {
            if ($group->name && preg_match('/^(\d)/', $group->name, $matches)) {
                DB::table('groups')
                    ->where('id', $group->id)
                    ->update(['year' => (int) $matches[1]]);
            }
        }

        if (Schema::hasTable('group_user')) {
            // 2. Auto-set main_group_id on users from pivot
            $classGroupUsers = DB::table('group_user')
                ->join('groups', 'group_user.group_id', '=', 'groups.id')
                ->whereNotNull('groups.year')
                ->select('group_user.user_id', 'group_user.group_id')
                ->get()
                ->unique('user_id');

            foreach ($classGroupUsers as $row) {
                DB::table('users')
                    ->where('id', $row->user_id)
                    ->whereNull('main_group_id')
                    ->update(['main_group_id' => $row->group_id]);
            }

            // 3. Set is_teacher from pivot (users who have a group with code 'LKR')
            $teacherUserIds = DB::table('group_user')
                ->join('groups', 'group_user.group_id', '=', 'groups.id')
                ->where('groups.code', 'LKR')
                ->pluck('group_user.user_id');

            if ($teacherUserIds->isNotEmpty()) {
                DB::table('users')
                    ->whereIn('id', $teacherUserIds)
                    ->update(['is_teacher' => true]);
            }

            // 4. Delete non-class groups
            $nonClassGroupIds = DB::table('groups')->whereNull('year')->pluck('id');

            if ($nonClassGroupIds->isNotEmpty()) {
                DB::table('group_user')->whereIn('group_id', $nonClassGroupIds)->delete();
                DB::table('groups')->whereIn('id', $nonClassGroupIds)->delete();
            }
        }

        // 5. Drop the group_user pivot table
        Schema::dropIfExists('group_user');
    }

    public function down(): void
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['group_id', 'user_id']);
            $table->index('user_id');
            $table->index('group_id');
        });
    }
};
