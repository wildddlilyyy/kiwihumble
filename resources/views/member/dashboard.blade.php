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

                    <dl class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-lg bg-slate-50 p-4">
                            <dt class="text-xs font-black uppercase text-slate-500">Phone</dt>
                            <dd class="mt-1 text-lg font-black">{{ $member->phone ?: 'Not set' }}</dd>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-4">
                            <dt class="text-xs font-black uppercase text-slate-500">Mom</dt>
                            <dd class="mt-1 text-lg font-black">{{ $member->mom_name ?: 'Not set' }}</dd>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-4">
                            <dt class="text-xs font-black uppercase text-slate-500">Dad</dt>
                            <dd class="mt-1 text-lg font-black">{{ $member->dad_name ?: 'Not set' }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('logout') }}" class="mt-6">
                        @csrf
                        <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
