<x-layouts.app title="Members">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Backend</p>
                    <h1 class="text-2xl font-black text-kiwi-ink">Members</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a class="rounded-lg px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.dashboard') }}">
                        Dashboard
                    </a>
                    <a class="rounded-lg border border-kiwi-blue px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.class-shirt-orders.export') }}">
                        Export Shirts
                    </a>
                    <a class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" href="{{ route('backend.members.create') }}">
                        Add Member
                    </a>
                </div>
            </div>
        </header>

        <section class="mx-auto max-w-6xl px-6 py-8">
            @if (session('status'))
                <div class="mb-5 rounded-lg bg-emerald-50 p-3 text-sm font-bold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-black/5">
                <table class="w-full min-w-[1080px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">NO.</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Birthday</th>
                            <th class="px-4 py-3">Mom</th>
                            <th class="px-4 py-3">Mom Phone</th>
                            <th class="px-4 py-3">Dad</th>
                            <th class="px-4 py-3">Dad Phone</th>
                            <th class="px-4 py-3">Shirts</th>
                            <th class="px-4 py-3">Password</th>
                            <th class="px-4 py-3">Updated</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($members as $member)
                            <tr>
                                <td class="px-4 py-3 font-black text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 font-bold text-kiwi-ink">{{ $member->name }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $member->birthday?->format('Y-m-d') ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $member->mom_name ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $member->mom_phone ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $member->dad_name ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $member->dad_phone ?: '-' }}</td>
                                <td class="px-4 py-3">
                                    <a
                                        class="inline-flex items-center justify-center rounded-lg border border-kiwi-blue px-3 py-2 text-sm font-black text-kiwi-blue transition hover:bg-kiwi-blue hover:text-white"
                                        href="{{ route('backend.members.class-shirt-order', $member) }}"
                                    >
                                        {{ $member->classShirtOrder?->totalQuantity() ?? 0 }} pcs
                                    </a>
                                </td>
                                <td class="px-4 py-3 font-bold text-amber-700">{{ $member->login_password ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $member->updated_at?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a class="font-black text-kiwi-blue hover:text-kiwi-ink" href="{{ route('backend.members.edit', $member) }}">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-8 text-center font-semibold text-slate-500" colspan="12">
                                    No members yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</x-layouts.app>
