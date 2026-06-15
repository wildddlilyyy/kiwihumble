<x-layouts.app title="Admin Login">
    <main class="grid min-h-screen place-items-center bg-kiwi-cream px-6 py-10">
        <section class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-black/5">
            <div class="mx-auto mb-6 w-28">
                <x-kiwi-loader />
            </div>

            <h1 class="text-2xl font-black tracking-normal text-kiwi-ink">Admin Login</h1>
            <p class="mt-2 text-sm text-slate-600">KIWI GROUP Humble Graduation Trip dashboard</p>

            <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                @csrf

                <label class="block">
                    <span class="text-sm font-bold text-slate-700">Email</span>
                    <input
                        class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </label>

                <label class="block">
                    <span class="text-sm font-bold text-slate-700">Password</span>
                    <input
                        class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                        type="password"
                        name="password"
                        required
                    >
                </label>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input class="rounded border-slate-300 text-kiwi-blue focus:ring-kiwi-blue" type="checkbox" name="remember" value="1">
                    Remember me
                </label>

                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 p-3 text-sm font-semibold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button class="w-full rounded-lg bg-kiwi-blue px-4 py-3 font-black text-white transition hover:bg-kiwi-ink" type="submit">
                    Sign in
                </button>
            </form>
        </section>
    </main>
</x-layouts.app>
