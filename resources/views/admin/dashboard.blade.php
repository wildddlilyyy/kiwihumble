<x-layouts.app title="Admin Dashboard">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Admin</p>
                    <h1 class="text-2xl font-black text-kiwi-ink">KIWI GROUP Dashboard</h1>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                        Sign out
                    </button>
                </form>
            </div>
        </header>

        <section class="mx-auto grid max-w-6xl gap-6 px-6 py-8 lg:grid-cols-[280px_1fr]">
            <aside class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="mx-auto w-36">
                    <x-kiwi-loader />
                </div>
                <p class="mt-4 text-center text-sm font-semibold text-slate-600">
                    Manage the public trip details and countdown date.
                </p>
            </aside>

            <div class="space-y-6">
                <form method="POST" action="{{ route('admin.settings.update') }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                    @csrf

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-kiwi-gold">Site Settings</p>
                            <h2 class="text-xl font-black text-kiwi-ink">Trip Details</h2>
                        </div>

                        <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                            Save Settings
                        </button>
                    </div>

                    @if (session('status'))
                        <div class="mt-5 rounded-lg bg-emerald-50 p-3 text-sm font-bold text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mt-5 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mt-6 grid gap-5">
                        <label class="block">
                            <span class="text-sm font-bold text-slate-700">Trip Title</span>
                            <input
                                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                type="text"
                                name="trip_title"
                                value="{{ old('trip_title', $settings->firstWhere('key', 'trip_title')?->value) }}"
                                required
                            >
                        </label>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Trip Date</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="date"
                                    name="trip_date"
                                    value="{{ old('trip_date', $settings->firstWhere('key', 'trip_date')?->value) }}"
                                    required
                                >
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Timezone</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="timezone"
                                    value="{{ old('timezone', $settings->firstWhere('key', 'timezone')?->value) }}"
                                    required
                                >
                            </label>
                        </div>
                    </div>
                </form>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                    <h2 class="text-xl font-black text-kiwi-ink">Current Settings</h2>
                    <div class="mt-5 overflow-hidden rounded-lg border border-slate-200">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Key</th>
                                    <th class="px-4 py-3">Value</th>
                                    <th class="px-4 py-3">Type</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($settings as $setting)
                                    <tr>
                                        <td class="px-4 py-3 font-bold text-kiwi-ink">{{ $setting->key }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $setting->value }}</td>
                                        <td class="px-4 py-3 text-slate-500">{{ $setting->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
