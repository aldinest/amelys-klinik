<section class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
    <header class="border-b border-gray-100 pb-4 mb-6">
        <h2 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-magic mr-2 text-blue-600"></i>
            Ganti Kata Sandi Cepat
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Langsung masukkan sandi baru tanpa perlu konfirmasi sandi lama.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="password" :value="__('Kata Sandi Baru')" class="font-semibold" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Ketik sandi baru..." />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Sandi Baru')" class="font-semibold" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Ulangi sandi baru..." />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md font-bold text-xs uppercase shadow-md hover:bg-blue-700">
                {{ __('Update Sekarang') }}
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-green-600 font-bold animate-bounce">
                    <i class="fas fa-check-circle mr-1"></i> Berhasil diganti!
                </p>
            @endif
        </div>
    </form>
</section>