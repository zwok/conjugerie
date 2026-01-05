<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ConjugationPractice;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/auth/redirect', function () {
    return Socialite::driver('smartschool')->setScopes(['userinfo groupinfo'])->redirect();
})->name('smartschool.redirect');

Route::get('/auth/callback', function () {
    $smartSchoolUser = Socialite::driver('smartschool')->user();

    // Create or update the local user
    $user = User::updateOrCreate(
        [
            'email' => $smartSchoolUser->user['username'],
        ],
        [
            'password' => Hash::make($smartSchoolUser->user['username']),
            'name' => $smartSchoolUser->user['name'] . ' ' . $smartSchoolUser->user['surname'],
            'smartschool_id' => $smartSchoolUser->user['userID'],
            'smartschool_username' => $smartSchoolUser->user['username'],
            'smartschool_platform' => $smartSchoolUser->user['platform'],
        ]
    );

    // Sync Smartschool groups to local DB
    $platform = $smartSchoolUser->user['platform'] ?? null;
    $groups = $smartSchoolUser->user['groups'] ?? [];

    $groupIds = [];
    if ($platform && is_array($groups)) {
        foreach ($groups as $g) {
            // Expected Smartschool fields: groupID, name, description, code
            $group = Group::updateOrCreate(
                [
                    'external_id' => $g['groupID'] ?? ($g['id'] ?? null),
                    'platform' => $platform,
                ],
                [
                    'name' => $g['name'] ?? null,
                    'description' => $g['description'] ?? null,
                    'code' => $g['code'] ?? null,
                ]
            );

            if ($group) {
                $groupIds[] = $group->id;
            }
        }
    }

    // Replace the user's current memberships with Smartschool memberships
    $user->groups()->sync($groupIds);

    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('/practice', function () {
    return view('practice');
})->name('practice');

Route::get('/dashboard', function () {
    $user = Auth::user()->load('groups');
    return view('dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__ . '/auth.php';
