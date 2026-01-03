<x-main-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Your account') }}</h3>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                            <dd class="font-medium break-all">{{ $user->smartschool_platform ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">{{ __('Teacher') }}</dt>
                            <dd class="font-medium">
                                @if($user->is_teacher)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-100 text-green-800 px-2 py-0.5 text-sm">{{ __('Yes') }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 text-gray-800 px-2 py-0.5 text-sm">{{ __('No') }}</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Your groups') }}</h3>
                    @if($user->groups->isEmpty())
                        <p class="text-gray-600">{{ __('You are not a member of any groups yet.') }}</p>
                    @else
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($user->groups as $group)
                                <li>
                                    <span class="font-medium">{{ $group->name }}</span>
                                    @if($group->code)
                                        <span class="text-gray-500">({{ $group->code }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
