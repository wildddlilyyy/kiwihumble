@php
    use App\Models\TripRegistration;
@endphp

<x-layouts.app title="Trip Registrations">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-4">
                <div><p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Backend</p><h1 class="text-2xl font-black text-kiwi-ink">Trip Registrations</h1></div>
                <div class="flex flex-wrap items-center gap-3">@include('backend.partials.navigation')<a class="rounded-lg border border-kiwi-blue px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.trip.export') }}">Export Trip</a><form method="POST" action="{{ route('backend.logout') }}">@csrf<button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">Sign out</button></form></div>
            </div>
        </header>
        <section class="mx-auto max-w-6xl space-y-6 px-6 py-8">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <p class="text-sm font-bold text-slate-500">參加組數</p>
                    <p class="mt-2 text-3xl font-black text-kiwi-ink">{{ $stats['groups'] }}</p>
                    <p class="mt-1 text-xs font-bold text-slate-400">每位同學一組</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <p class="text-sm font-bold text-slate-500">大人總人數</p>
                    <p class="mt-2 text-3xl font-black text-kiwi-ink">{{ $stats['adults'] }}</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <p class="text-sm font-bold text-slate-500">小孩總人數</p>
                    <p class="mt-2 text-3xl font-black text-kiwi-ink">{{ $stats['children'] }}</p>
                    <p class="mt-1 text-xs font-bold text-slate-500">0-6Y：{{ $stats['children_0_6'] }}　7-12Y：{{ $stats['children_7_12'] }}</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <p class="text-sm font-bold text-slate-500">房間數</p>
                    <p class="mt-2 text-3xl font-black text-kiwi-ink">{{ $stats['rooms_double'] + $stats['rooms_quad'] + $stats['rooms_six'] }}</p>
                    <p class="mt-1 text-xs font-bold text-slate-500">雙人 {{ $stats['rooms_double'] }}　四人 {{ $stats['rooms_quad'] }}　六人 {{ $stats['rooms_six'] }}</p>
                </div>
            </section>
            <section class="overflow-x-auto rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5"><h2 class="text-xl font-black text-kiwi-ink">畢旅人數及房間登記</h2><table class="mt-6 w-full min-w-[980px] text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">NO.</th><th class="px-4 py-3">Name</th><th class="px-4 py-3">大人</th><th class="px-4 py-3">小孩</th><th class="px-4 py-3">小孩年齡</th><th class="px-4 py-3">房間</th><th class="px-4 py-3">房型</th><th class="px-4 py-3">送出時間</th></tr></thead><tbody class="divide-y divide-slate-200">
                @forelse ($members as $member)
                    @php $registration = $member->tripRegistration; $childAges = collect($registration?->child_ages ?? [])->map(fn ($age) => $age.'Y')->join(', '); $roomTypes = collect($registration?->room_types ?? [])->map(fn ($type) => TripRegistration::roomTypeLabel($type))->join('、'); @endphp
                    <tr><td class="px-4 py-3 font-black text-slate-500">{{ $loop->iteration }}</td><td class="px-4 py-3 font-bold text-kiwi-ink">{{ $member->name }}</td><td class="px-4 py-3 text-slate-700">{{ $registration?->adults_count ?? 0 }}</td><td class="px-4 py-3 text-slate-700">{{ $registration?->children_count ?? 0 }}</td><td class="px-4 py-3 text-slate-700">{{ $childAges ?: '-' }}</td><td class="px-4 py-3 text-slate-700">{{ $registration?->room_count ?? 0 }}</td><td class="max-w-xs px-4 py-3 text-slate-700">{{ $roomTypes ?: '-' }}</td><td class="px-4 py-3 text-slate-500">{{ $registration?->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?: '-' }}</td></tr>
                @empty
                    <tr><td class="px-4 py-8 text-center font-semibold text-slate-500" colspan="8">No members yet.</td></tr>
                @endforelse
            </tbody></table></section>
        </section>
    </main>
</x-layouts.app>
