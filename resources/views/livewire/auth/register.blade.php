<div>
    <div class="flex min-h-[80vh] items-center justify-center px-4 py-12">

        <div class="w-full max-w-md rounded-2xl border border-stone-200 bg-white p-8 shadow-sm">

            <div class="mb-8 text-center">
                <div class="text-4xl">☕</div>

                <h1 class="mt-4 text-3xl font-bold text-stone-900">
                    Join Our Coffee Shop
                </h1>

                <p class="mt-2 text-stone-500">
                    Create an account to start ordering.
                </p>
            </div>

            <form wire:submit="register" class="space-y-5">

                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-stone-700">
                        Full Name
                    </label>

                    <input
                        id="name"
                        type="text"
                        wire:model="name"
                        autocomplete="name"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                    >

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-stone-700">
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        wire:model="email"
                        autocomplete="email"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-stone-700">
                        Password
                    </label>

                    <input
                        id="password"
                        type="password"
                        wire:model="password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-stone-700">
                        Confirm Password
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        wire:model="password_confirmation"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 outline-none focus:border-emerald-700"
                    >
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="register"
                    class="w-full rounded-xl bg-emerald-800 px-5 py-3 font-semibold text-white hover:bg-emerald-900 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="register">
                        Create Account
                    </span>

                    <span wire:loading wire:target="register">
                        Creating Account...
                    </span>
                </button>

            </form>

            <p class="mt-6 text-center text-sm text-stone-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-semibold text-emerald-800 hover:underline">
                    Log in
                </a>
            </p>

        </div>

    </div>
</div>
