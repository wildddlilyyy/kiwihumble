<x-layouts.app title="Backend Dashboard">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Backend</p>
                    <h1 class="text-2xl font-black text-kiwi-ink">KIWI HUMBLE Dashboard</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    @include('backend.partials.navigation')
                    <form method="POST" action="{{ route('backend.logout') }}">
                        @csrf
                        <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">Sign out</button>
                    </form>
                </div>
            </div>
        </header>

        <section class="mx-auto grid max-w-6xl gap-6 px-6 py-8 lg:grid-cols-[280px_1fr]">
            <aside class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="mx-auto w-36"><x-kiwi-loader /></div>
                <p class="mt-4 text-center text-sm font-semibold text-slate-600">Choose Shirts, Trip, or Member from the menu to manage registrations.</p>
            </aside>
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <p class="text-xs font-black uppercase tracking-[0.18em] text-kiwi-gold">Welcome</p>
                <h2 class="mt-2 text-2xl font-black text-kiwi-ink">Manage member registrations</h2>
                <p class="mt-3 text-sm font-semibold leading-6 text-slate-600">Use the navigation above to open the shirt order list, trip registration list, or member records.</p>
            </div>
        </section>
    </main>
</x-layouts.app>
