<x-layouts.app title="Member Home">
    <main class="kiwi-grid-bg min-h-screen px-6 py-10 text-white">
        <section class="mx-auto flex min-h-screen max-w-4xl items-center">
            <div class="w-full">
                <div class="mx-auto w-40">
                    <x-kiwi-loader />
                </div>

                <div class="mt-8 rounded-xl bg-white/95 p-6 text-kiwi-ink shadow-xl shadow-black/10 ring-1 ring-white/40">
                    <p class="font-hand text-2xl text-kiwi-gold">Welcome back</p>
                    <h1 class="font-display text-4xl font-extrabold">{{ $member->name }}</h1>

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

                    <form method="POST" action="{{ route('member.profile.update') }}" class="mt-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Name</span>
                                <input class="mt-2 w-full rounded-lg border-slate-300 bg-slate-100 text-slate-500" type="text" value="{{ $member->name }}" disabled>
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Birthday</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="date"
                                    name="birthday"
                                    value="{{ old('birthday', $member->birthday?->format('Y-m-d')) }}"
                                >
                            </label>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Mom</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="mom_name"
                                    value="{{ old('mom_name', $member->mom_name) }}"
                                >
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Phone</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="mom_phone"
                                    value="{{ old('mom_phone', $member->mom_phone) }}"
                                >
                            </label>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Dad</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="dad_name"
                                    value="{{ old('dad_name', $member->dad_name) }}"
                                >
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Phone</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="dad_phone"
                                    value="{{ old('dad_phone', $member->dad_phone) }}"
                                >
                            </label>
                        </div>

                        <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                            Save Profile
                        </button>
                    </form>

                    <form method="POST" action="{{ route('member.logout') }}" class="mt-4">
                        @csrf
                        <button class="rounded-lg border border-kiwi-blue px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-50" type="submit">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
