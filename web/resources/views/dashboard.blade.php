<x-main-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profil + Actions rapides -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <!-- Avatar initiales -->
                            <div class="h-14 w-14 rounded-full bg-secondary text-white flex items-center justify-center text-xl font-bold">
                                {{ str(mb_substr($user->name, 0, 1))->upper() }}
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-secondary">{{ $user->name }}</h3>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $user->is_teacher ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        @if($user->is_teacher)
                                            {{ __('Teacher') }}: {{ __('Yes') }}
                                        @else
                                            {{ __('Teacher') }}: {{ __('No') }}
                                        @endif
                                    </span>
                                    @if($user->email)
                                        <span class="text-sm text-gray-500">{{ $user->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Informations du compte -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-secondary">{{ __('Your account') }}</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('Name') }}</dt>
                            <dd class="font-medium">{{ $user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('Email') }}</dt>
                            <dd class="font-medium">{{ $user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('Smartschool ID') }}</dt>
                            <dd class="font-medium">{{ $user->smartschool_id ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('Smartschool username') }}</dt>
                            <dd class="font-medium">{{ $user->smartschool_username ?? '—' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm text-gray-500">{{ __('Smartschool platform') }}</dt>
                            <dd class="font-medium break-all">
                                @if($user->smartschool_platform)
                                    <a href="{{ $user->smartschool_platform }}" target="_blank" rel="noopener" class="text-secondary underline hover:no-underline">{{ $user->smartschool_platform }}</a>
                                @else
                                    —
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Groupes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4 text-secondary">{{ __('Your groups') }}</h3>
                    @if($user->groups->isEmpty())
                        <p class="text-gray-600">{{ __('You are not a member of any groups yet.') }}</p>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @foreach($user->groups as $group)
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary-light text-secondary">
                                    <span class="font-medium">{{ $group->name }}</span>
                                    @if($group->code)
                                        <span class="text-xs bg-white text-secondary px-2 py-0.5 rounded-full border border-secondary/20">{{ $group->code }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
