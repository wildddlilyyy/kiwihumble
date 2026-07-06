<x-layouts.app title="Add Member">
    <main class="min-h-screen bg-slate-100">
        <section class="mx-auto max-w-3xl px-6 py-8">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-kiwi-gold">Members</p>
                        <h1 class="text-2xl font-black text-kiwi-ink">Add Member</h1>
                    </div>
                    <a class="font-black text-kiwi-blue hover:text-kiwi-ink" href="{{ route('backend.members.index') }}">Back</a>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('backend.members.store') }}" class="mt-6">
                    @include('backend.members.form')

                    <button class="mt-6 rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                        Create Member
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-layouts.app>
