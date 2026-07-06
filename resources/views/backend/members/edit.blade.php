<x-layouts.app title="Edit Member">
    <main class="min-h-screen bg-slate-100">
        <section class="mx-auto grid max-w-5xl gap-6 px-6 py-8 lg:grid-cols-[1fr_360px]">
            <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-kiwi-gold">Members</p>
                        <h1 class="text-2xl font-black text-kiwi-ink">Edit {{ $member->name }}</h1>
                    </div>
                    <a class="font-black text-kiwi-blue hover:text-kiwi-ink" href="{{ route('backend.members.index') }}">Back</a>
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

                <form method="POST" action="{{ route('backend.members.update', $member) }}" class="mt-6">
                    @method('PUT')
                    @include('backend.members.form', ['member' => $member])

                    <button class="mt-6 rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                        Save Member
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('backend.members.password.update', $member) }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                @csrf
                <h2 class="text-xl font-black text-kiwi-ink">Reset Password</h2>
                <p class="mt-2 text-sm font-semibold text-slate-600">Set a new password for this member.</p>

                <label class="mt-5 block">
                    <span class="text-sm font-bold text-slate-700">New Password</span>
                    <input class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" type="password" name="password" required>
                </label>

                <label class="mt-5 block">
                    <span class="text-sm font-bold text-slate-700">Confirm Password</span>
                    <input class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" type="password" name="password_confirmation" required>
                </label>

                <button class="mt-6 rounded-lg bg-kiwi-orange px-4 py-2 text-sm font-black text-white hover:bg-kiwi-brown" type="submit">
                    Reset Password
                </button>
            </form>
        </section>
    </main>
</x-layouts.app>
