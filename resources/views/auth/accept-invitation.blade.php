<x-guest-layout>
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc ps-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="mb-4 text-sm text-gray-700">
        You were invited to join
        <strong>{{ $invitation->company?->name ?? 'Company' }}</strong>
        as <strong>{{ $roleName ?? 'User' }}</strong>.
    </p>

    <form method="POST" action="{{ route('invitation.accept.store', $token) }}">
        @csrf

        <div>
            <x-input-label :value="__('Email')" />
            <x-text-input class="block mt-1 w-full" type="email" :value="$invitation->email" disabled />
        </div>

        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-primary-button>
                {{ __('Accept Invitation') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
