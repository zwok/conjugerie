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

    // Detect teacher status and main class group from Smartschool groups
    $platform = $smartSchoolUser->user['platform'] ?? null;
    $groups = $smartSchoolUser->user['groups'] ?? [];

    $isTeacher = false;
    $classGroup = null;

    if ($platform && is_array($groups)) {
        foreach ($groups as $g) {
            $code = $g['code'] ?? null;
            $name = $g['name'] ?? '';

            // Detect teacher by LKR group code
            if ($code === 'LKR') {
                $isTeacher = true;
            }

            // Class group: name starts with a digit (e.g. "3A", "5B")
            if (! $classGroup && preg_match('/^(\d)/', $name, $matches)) {
                $classGroup = Group::updateOrCreate(
                    [
                        'external_id' => $g['groupID'] ?? ($g['id'] ?? null),
                        'platform' => $platform,
                    ],
                    [
                        'name' => $name,
                        'description' => $g['description'] ?? null,
                        'code' => $code,
                        'year' => (int) $matches[1],
                    ]
                );
            }
        }
    }

    $user->update(['is_teacher' => $isTeacher]);

    // Auto-detect main group if not already set (admin overrides persist)
    if (! $user->main_group_id && $classGroup) {
        $user->update(['main_group_id' => $classGroup->id]);
    }

    Auth::login($user);

    return redirect('/dashboard');
});

Route::get('/practice', function () {
    return view('practice');
})->name('practice');

Route::get('/dashboard', function () {
    $user = Auth::user()->load('mainGroup');

    // Global statistics
    $totalAnswers = $user->studentAnswers()->count();
    $correctAnswers = $user->studentAnswers()->where('is_correct', true)->count();
    $globalPercentage = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 1) : 0;

    // This week statistics
    $weekStart = now()->startOfWeek();
    $weekAnswers = $user->studentAnswers()->where('created_at', '>=', $weekStart)->count();
    $weekCorrect = $user->studentAnswers()->where('is_correct', true)->where('created_at', '>=', $weekStart)->count();
    $weekPercentage = $weekAnswers > 0 ? round(($weekCorrect / $weekAnswers) * 100, 1) : 0;

    return view('dashboard', compact('user', 'totalAnswers', 'correctAnswers', 'globalPercentage', 'weekAnswers', 'weekCorrect', 'weekPercentage'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__ . '/auth.php';
