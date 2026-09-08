@php
    use App\Models\ClassShirtOrder;
    use App\Models\TripRegistration;
@endphp

<x-layouts.app title="Backend Dashboard">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Backend</p>
                    <h1 class="text-2xl font-black text-kiwi-ink">KIWI HUMBLE Dashboard</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a class="rounded-lg px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.members.index') }}">
                        Members
                    </a>
                    <form method="POST" action="{{ route('backend.logout') }}">
                        @csrf
                        <button class="rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <section class="mx-auto grid max-w-6xl gap-6 px-6 py-8 lg:grid-cols-[280px_1fr]">
            <aside class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                <div class="mx-auto w-36">
                    <x-kiwi-loader />
                </div>
                <p class="mt-4 text-center text-sm font-semibold text-slate-600">
                    Manage public trip details and fixed member records.
                </p>
            </aside>

            <div class="space-y-6" x-data="{ form: 'trip' }">
                <form method="POST" action="{{ route('backend.settings.update') }}" class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
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

                <section class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-kiwi-gold">Registration Lists</p>
                            <h2 class="text-xl font-black text-kiwi-ink">會員登記資料</h2>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button
                                class="rounded-lg px-4 py-2 text-sm font-black transition"
                                :class="form === 'trip' ? 'bg-kiwi-blue text-white' : 'bg-slate-100 text-kiwi-blue hover:bg-slate-200'"
                                type="button"
                                @click="form = 'trip'"
                            >
                                畢旅
                            </button>
                            <button
                                class="rounded-lg px-4 py-2 text-sm font-black transition"
                                :class="form === 'shirt' ? 'bg-kiwi-blue text-white' : 'bg-slate-100 text-kiwi-blue hover:bg-slate-200'"
                                type="button"
                                @click="form = 'shirt'"
                            >
                                班服登記
                            </button>
                        </div>
                    </div>

                    <div class="mt-6 overflow-x-auto" x-show="form === 'trip'">
                        <table class="w-full min-w-[980px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">NO.</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">大人</th>
                                    <th class="px-4 py-3">小孩</th>
                                    <th class="px-4 py-3">小孩年齡</th>
                                    <th class="px-4 py-3">房間</th>
                                    <th class="px-4 py-3">房型</th>
                                    <th class="px-4 py-3">送出時間</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($members as $member)
                                    @php
                                        $registration = $member->tripRegistration;
                                        $childAges = collect($registration?->child_ages ?? [])->map(fn ($age) => $age.'Y')->join(', ');
                                        $roomTypes = collect($registration?->room_types ?? [])->map(fn ($type) => TripRegistration::roomTypeLabel($type))->join('、');
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 font-black text-slate-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 font-bold text-kiwi-ink">{{ $member->name }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $registration?->adults_count ?? 0 }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $registration?->children_count ?? 0 }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $childAges ?: '-' }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $registration?->room_count ?? 0 }}</td>
                                        <td class="max-w-xs px-4 py-3 text-slate-700">{{ $roomTypes ?: '-' }}</td>
                                        <td class="px-4 py-3 text-slate-500">{{ $registration?->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?: '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td class="px-4 py-8 text-center font-semibold text-slate-500" colspan="8">No members yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 overflow-x-auto" x-show="form === 'shirt'">
                        <table class="w-full min-w-[900px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">NO.</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Shirts</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Payment</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Updated</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($members as $member)
                                    @php
                                        $paymentStatusBadgeStyle = match ($member->classShirtOrder?->payment_status ?? ClassShirtOrder::PAYMENT_STATUS_UNPAID) {
                                            ClassShirtOrder::PAYMENT_STATUS_PENDING => 'background-color: rgb(254 243 199); border-color: rgb(252 211 77);',
                                            ClassShirtOrder::PAYMENT_STATUS_COMPLETED => 'background-color: rgb(220 252 231); border-color: rgb(134 239 172);',
                                            default => 'background-color: rgb(241 245 249); border-color: rgb(203 213 225);',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-3 font-black text-slate-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3 font-bold text-kiwi-ink">{{ $member->name }}</td>
                                        <td class="px-4 py-3">
                                            <a class="font-black text-kiwi-blue hover:text-kiwi-ink" href="{{ route('backend.members.class-shirt-order', $member) }}">
                                                {{ $member->classShirtOrder?->totalQuantity() ?? 0 }} pcs
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 font-bold text-slate-700">NT$ {{ number_format($member->classShirtOrder?->totalAmount() ?? 0) }}</td>
                                        <td class="px-4 py-3 text-slate-700">
                                            {{ $member->classShirtOrder?->payment_method_label ?? '-' }}
                                            @if ($member->classShirtOrder?->payment_account_last_five)
                                                / {{ $member->classShirtOrder->payment_account_last_five }}
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-black text-kiwi-ink" style="{{ $paymentStatusBadgeStyle }}">
                                                {{ $member->classShirtOrder?->payment_status_label ?? ClassShirtOrder::paymentStatusLabel(null) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-500">{{ $member->updated_at?->format('Y-m-d') }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a class="font-black text-kiwi-blue hover:text-kiwi-ink" href="{{ route('backend.members.edit', $member) }}">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="px-4 py-8 text-center font-semibold text-slate-500" colspan="8">No members yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </section>
    </main>
</x-layouts.app>
