<x-app-layout>
    <div class="max-w-xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-2">Ganti Password Pertama</h1>
        <p class="text-sm text-gray-600 mb-6">Demi keamanan akun, silakan ganti password Anda sebelum melanjutkan.</p>

        <form method="POST" action="{{ route('first-login.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="password" :value="'Password Baru'" />
                <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="'Konfirmasi Password Baru'" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="block mt-1 w-full" required />
            </div>

            <x-primary-button>
                Simpan Password
            </x-primary-button>
        </form>
    </div>
</x-app-layout>