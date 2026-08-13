@php
    use App\Models\ClassShirtOrder;

    $order = $member->classShirtOrder;
    $shirtRows = old('items', $order?->items ?? []);
    $shirtRows = array_map(function ($item) {
        $item['size'] = ClassShirtOrder::normalizeSize($item['size'] ?? null);

        return $item;
    }, array_values($shirtRows));
    $paymentMethod = old('payment_method', $order?->payment_method ?? ClassShirtOrder::PAYMENT_METHOD_TRANSFER);
    $paymentLastFive = old('payment_account_last_five', $order?->payment_account_last_five);
    $paymentStatus = old('payment_status', $order?->payment_status ?? ClassShirtOrder::PAYMENT_STATUS_UNPAID);
    $paymentStatusBadgeStyle = match ($order?->payment_status ?? ClassShirtOrder::PAYMENT_STATUS_UNPAID) {
        ClassShirtOrder::PAYMENT_STATUS_PENDING => 'background-color: rgb(254 243 199); border-color: rgb(252 211 77);',
        ClassShirtOrder::PAYMENT_STATUS_COMPLETED => 'background-color: rgb(220 252 231); border-color: rgb(134 239 172);',
        default => 'background-color: rgb(241 245 249); border-color: rgb(203 213 225);',
    };
@endphp

<x-layouts.app title="Class Shirt Order">
    <main class="min-h-screen bg-slate-100">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-kiwi-gold">Backend</p>
                    <h1 class="text-2xl font-black text-kiwi-ink">{{ $member->name }} Shirt Order</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a class="rounded-lg px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.members.index') }}">
                        Members
                    </a>
                    <a class="rounded-lg border border-kiwi-blue px-4 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-100" href="{{ route('backend.members.edit', $member) }}">
                        Edit Member
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

            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="grid gap-5 lg:grid-cols-[320px_1fr]">
                <aside class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                    <p class="text-xs font-black uppercase tracking-wide text-slate-500">Member Profile</p>
                    <h2 class="mt-2 text-2xl font-black text-kiwi-ink">{{ $member->name }}</h2>

                    <dl class="mt-5 space-y-3 text-sm">
                        <div>
                            <dt class="font-black text-slate-500">Profile updated</dt>
                            <dd class="mt-1 text-slate-800">{{ $member->updated_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-black text-slate-500">Mom</dt>
                            <dd class="mt-1 text-slate-800">{{ $member->mom_name ?: '-' }} / {{ $member->mom_phone ?: '-' }}</dd>
                        </div>
                        <div>
                            <dt class="font-black text-slate-500">Dad</dt>
                            <dd class="mt-1 text-slate-800">{{ $member->dad_name ?: '-' }} / {{ $member->dad_phone ?: '-' }}</dd>
                        </div>
                    </dl>
                </aside>

                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                    <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 xl:flex-row xl:items-start xl:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-wide text-slate-500">Class Shirt Order</p>
                            <div class="mt-2 space-y-1 text-[1.2rem] font-black leading-tight text-kiwi-ink">
                                <p>{{ $order?->totalQuantity() ?? 0 }} pcs</p>
                                <p>NT$ {{ number_format($order?->totalAmount() ?? 0) }}</p>
                            </div>
                        </div>

                        <dl class="grid gap-3 text-sm sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-lg border border-[#c9d3dd] px-4 py-3" style="background-color: rgb(241 245 249);">
                                <dt class="font-black text-slate-500">Submitted</dt>
                                <dd class="mt-1 font-bold text-slate-800">{{ $order?->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?: '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-[#c9d3dd] px-4 py-3" style="background-color: rgb(241 245 249);">
                                <dt class="font-black text-slate-500">Order updated</dt>
                                <dd class="mt-1 font-bold text-slate-800">{{ $order?->updated_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?: '-' }}</dd>
                            </div>
                            <div class="rounded-lg border border-[#c9d3dd] px-4 py-3" style="background-color: rgb(241 245 249);">
                                <dt class="font-black text-slate-500">Payment</dt>
                                <dd class="mt-1 font-bold text-slate-800">{{ $order?->payment_method_label ?? '-' }} {{ $order?->payment_account_last_five ? ' / '.$order->payment_account_last_five : '' }}</dd>
                            </div>
                            <div class="rounded-lg border border-[#c9d3dd] px-4 py-3" style="background-color: rgb(241 245 249);">
                                <dt class="font-black text-slate-500">Status</dt>
                                <dd class="mt-2 inline-flex rounded-full border px-3 py-1 text-xs font-black text-kiwi-ink" style="{{ $paymentStatusBadgeStyle }}">
                                    {{ $order?->payment_status_label ?? ClassShirtOrder::paymentStatusLabel(null) }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <form
                        class="mt-6"
                        method="POST"
                        action="{{ route('backend.members.class-shirt-order.update', $member) }}"
                        x-data="{
                            rows: @js($shirtRows),
                            unitPrice: {{ ClassShirtOrder::UNIT_PRICE }},
                            childSizes: ['#6熱轉印', '#8', '#10'],
                            adultSizes: ['XS', 'S', 'M', 'L', 'XL', '2L', '3L', '5L'],
                            paymentMethod: @js($paymentMethod),
                            addItem() {
                                this.rows.push({ category: 'child', size: '#6熱轉印', quantity: 1 });
                            },
                            removeItem(index) {
                                this.rows.splice(index, 1);
                            },
                            normalizeSize(row) {
                                const sizes = row.category === 'adult' ? this.adultSizes : this.childSizes;

                                if (! sizes.includes(row.size)) {
                                    row.size = sizes[0];
                                }
                            },
                            totalQuantity() {
                                return this.rows.reduce((total, row) => total + Number(row.quantity || 0), 0);
                            },
                            totalAmount() {
                                return this.totalQuantity() * this.unitPrice;
                            },
                        }"
                    >
                        @csrf
                        @method('PUT')

                        <div class="rounded-xl bg-slate-50 p-6 text-center font-bold text-slate-500" x-show="rows.length === 0">
                            No shirt order items yet.
                        </div>

                        <div class="overflow-hidden rounded-xl border border-slate-200" x-show="rows.length > 0">
                            <table class="w-full min-w-[760px] text-left text-sm">
                                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                    <tr>
                                        <th class="px-4 py-3">NO.</th>
                                        <th class="px-4 py-3">Type</th>
                                        <th class="px-4 py-3">Size</th>
                                        <th class="px-4 py-3">Quantity</th>
                                        <th class="px-4 py-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <tr>
                                            <td class="px-4 py-3 font-black text-slate-500" x-text="index + 1"></td>
                                            <td class="px-4 py-3">
                                                <select
                                                    class="w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                                    x-model="row.category"
                                                    x-bind:name="'items[' + index + '][category]'"
                                                    @change="normalizeSize(row)"
                                                >
                                                    <option value="child">Child</option>
                                                    <option value="adult">Adult</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                <select
                                                    class="w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                                    x-model="row.size"
                                                    x-bind:name="'items[' + index + '][size]'"
                                                >
                                                    <optgroup label="Child">
                                                        <option value="#6熱轉印">#6熱轉印</option>
                                                        <option value="#8">#8</option>
                                                        <option value="#10">#10</option>
                                                    </optgroup>
                                                    <optgroup label="Adult">
                                                        <option value="XS">XS</option>
                                                        <option value="S">S</option>
                                                        <option value="M">M</option>
                                                        <option value="L">L</option>
                                                        <option value="XL">XL</option>
                                                        <option value="2L">2L</option>
                                                        <option value="3L">3L</option>
                                                        <option value="5L">5L</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input
                                                    class="w-28 rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                                    type="number"
                                                    min="1"
                                                    max="99"
                                                    x-model.number="row.quantity"
                                                    x-bind:name="'items[' + index + '][quantity]'"
                                                >
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-black text-red-600 hover:bg-red-50" type="button" @click="removeItem(index)">
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 rounded-xl bg-slate-50 p-4">
                            <p class="text-sm font-black text-kiwi-ink">
                                Live total:
                                <span x-text="totalQuantity()"></span> pcs ·
                                NT$ <span x-text="totalAmount().toLocaleString('zh-TW')"></span>
                            </p>
                        </div>

                        <div class="mt-5 grid gap-4 rounded-xl border border-slate-200 p-4 lg:grid-cols-3">
                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Payment method</span>
                                <select
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    name="payment_method"
                                    x-model="paymentMethod"
                                >
                                    @foreach (ClassShirtOrder::PAYMENT_METHOD_LABELS as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Account last five</span>
                                <input
                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                    type="text"
                                    name="payment_account_last_five"
                                    value="{{ $paymentLastFive }}"
                                    inputmode="numeric"
                                    maxlength="5"
                                    placeholder="Transfer only"
                                    :disabled="paymentMethod === 'cash'"
                                >
                            </label>

                            <label class="block">
                                <span class="text-sm font-bold text-slate-700">Payment status</span>
                                <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" name="payment_status">
                                    @foreach (ClassShirtOrder::PAYMENT_STATUS_LABELS as $value => $label)
                                        <option value="{{ $value }}" @selected($paymentStatus === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <p class="mt-3 text-sm font-bold text-slate-500">
                            Blank rows are ignored. To remove an item, clear its size and quantity or use Remove before saving.
                        </p>

                        <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <button class="rounded-lg border border-kiwi-blue px-4 py-3 text-sm font-black text-kiwi-blue hover:bg-slate-50" type="button" @click="addItem()">
                                Add item
                            </button>

                            <button class="rounded-lg bg-kiwi-blue px-5 py-3 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                                Save Shirt Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
