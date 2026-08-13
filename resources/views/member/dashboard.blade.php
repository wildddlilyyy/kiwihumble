@php
    use App\Models\ClassShirtOrder;

    $classShirtOrder = $member->classShirtOrder;
    $paymentStatusBadgeStyle = match ($classShirtOrder?->payment_status ?? ClassShirtOrder::PAYMENT_STATUS_UNPAID) {
        ClassShirtOrder::PAYMENT_STATUS_PENDING => 'background-color: rgb(254 243 199); border-color: rgb(252 211 77);',
        ClassShirtOrder::PAYMENT_STATUS_COMPLETED => 'background-color: rgb(220 252 231); border-color: rgb(134 239 172);',
        default => 'background-color: rgb(241 245 249); border-color: rgb(203 213 225);',
    };
@endphp

<x-layouts.app title="Member Home">
    <main class="kiwi-grid-bg min-h-screen px-6 py-10 text-white">
        <section class="mx-auto flex min-h-screen max-w-5xl items-center">
            <div class="w-full">
                <div class="mx-auto w-40">
                    <x-kiwi-loader />
                </div>

                <div
                    class="mt-8 rounded-xl bg-white/95 p-6 text-kiwi-ink shadow-xl shadow-black/10 ring-1 ring-white/40"
                    x-data="{ tab: new URLSearchParams(window.location.search).get('tab') === 'class-shirt' ? 'class-shirt' : 'profile' }"
                >
                    <p class="font-hand text-2xl text-kiwi-gold">Welcome back</p>
                    <h1 class="font-display text-4xl font-extrabold">{{ $member->name }}</h1>

                    <div class="mt-6 flex flex-wrap gap-2 border-b border-slate-200 pb-3">
                        <button
                            class="rounded-full px-4 py-2 text-sm font-black transition"
                            :class="tab === 'profile' ? 'bg-kiwi-blue text-white' : 'bg-slate-100 text-kiwi-blue hover:bg-slate-200'"
                            type="button"
                            @click="tab = 'profile'"
                        >
                            家長資料
                        </button>
                        <button
                            class="rounded-full px-4 py-2 text-sm font-black transition"
                            :class="tab === 'class-shirt' ? 'bg-kiwi-blue text-white' : 'bg-slate-100 text-kiwi-blue hover:bg-slate-200'"
                            type="button"
                            @click="tab = 'class-shirt'"
                        >
                            班服訂購
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

                    <div x-show="tab === 'profile'">
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
                    </div>

                    <div class="mt-6 space-y-6" x-show="tab === 'class-shirt'">
                        <img
                            class="w-full rounded-xl border border-slate-200 shadow-sm"
                            src="{{ asset('assets/class-shirt/green-decision.jpg') }}"
                            alt="Humble 班服尺寸表"
                        >

                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">班服尺寸 - 兒童 SIZE</div>
                                <table class="w-full text-center text-sm">
                                    <thead class="bg-sky-100 text-kiwi-blue">
                                        <tr class="h-12">
                                            <th class="px-3 py-3"></th>
                                            <th class="px-3 py-3">#6熱轉印</th>
                                            <th class="px-3 py-3">#8</th>
                                            <th class="px-3 py-3">#10</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 font-bold">
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">衣長</th><td>46</td><td>50</td><td>54</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">胸寬</th><td>34</td><td>37</td><td>40</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">肩寬</th><td>14</td><td>15</td><td>16</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">袖長</th><td>31</td><td>33</td><td>35</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">班服尺寸 - 成人 SIZE（單位 / cm）</div>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[620px] text-center text-sm">
                                        <thead class="bg-sky-100 text-kiwi-blue">
                                            <tr class="h-12">
                                                <th class="px-3 py-3"></th>
                                                <th class="px-3 py-3">XS</th>
                                                <th class="px-3 py-3">S</th>
                                                <th class="px-3 py-3">M</th>
                                                <th class="px-3 py-3">L</th>
                                                <th class="px-3 py-3">XL</th>
                                                <th class="px-3 py-3">2L</th>
                                                <th class="px-3 py-3">3L</th>
                                                <th class="px-3 py-3">5L</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 font-bold">
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">衣長</th><td>60</td><td>64</td><td>68</td><td>70</td><td>72</td><td>76</td><td>80</td><td>84</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">胸寬</th><td>43</td><td>46</td><td>50</td><td>53</td><td>56</td><td>60</td><td>64</td><td>72</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">肩寬</th><td>18</td><td>19</td><td>20</td><td>21</td><td>22</td><td>24</td><td>26</td><td>27</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">袖長</th><td>38</td><td>40</td><td>42</td><td>44</td><td>46</td><td>48</td><td>50</td><td>55</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm font-bold text-slate-500">* 尺寸為平量參考，可能因布料與測量方式有 ±2.5cm 誤差。</p>

                        <div
                            class="rounded-xl border border-slate-200 p-4"
                            x-data="classShirtOrderForm({
                                items: @js($classShirtOrder?->items ?? []),
                                submittedAt: @js($classShirtOrder?->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i')),
                                paymentMethod: @js($classShirtOrder?->payment_method ?? 'transfer'),
                                paymentAccountLastFive: @js($classShirtOrder?->payment_account_last_five ?? ''),
                                paymentStatusLabel: @js($classShirtOrder?->payment_status_label ?? '尚未付款'),
                                storeUrl: @js(route('member.class-shirt-order.store')),
                                csrfToken: @js(csrf_token()),
                            })"
                        >
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h2 class="text-xl font-black text-kiwi-ink">班服訂購內容</h2>
                                    <p class="mt-1 text-sm font-bold text-slate-500" x-show="submittedAt">
                                        訂單已送出，如需修改訂購內容請聯繫管理者。
                                    </p>
                                </div>

                                @if (! $classShirtOrder)
                                    <button
                                        class="grid size-11 place-items-center rounded-full bg-kiwi-blue text-2xl font-black text-white hover:bg-kiwi-ink"
                                        type="button"
                                        @click="addItem()"
                                        x-show="! submittedAt"
                                    >
                                        +
                                    </button>
                                @endif
                            </div>

                            <div class="mt-4 rounded-lg bg-emerald-50 p-3 text-sm font-bold text-emerald-700" x-show="status" x-text="status"></div>
                            <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-700" x-show="error" x-text="error"></div>

                            @if (! $classShirtOrder)
                                <div class="mt-4 space-y-3" x-show="! submittedAt">
                                    <template x-if="items.length === 0">
                                        <div class="rounded-lg bg-slate-50 p-6 text-center font-bold text-slate-500">
                                            尚未新增班服訂購內容。
                                        </div>
                                    </template>

                                    <template x-for="(item, index) in items" :key="index">
                                        <article class="grid gap-4 rounded-lg border border-slate-200 p-4 sm:grid-cols-[1fr_1fr_120px_auto]">
                                            <label class="block">
                                                <span class="text-sm font-bold text-slate-700">類別</span>
                                                <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" x-model="item.category" @change="normalizeSize(item)">
                                                    <option value="child">兒童</option>
                                                    <option value="adult">成人</option>
                                                </select>
                                            </label>

                                            <label class="block">
                                                <span class="text-sm font-bold text-slate-700">尺寸</span>
                                                <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" x-model="item.size">
                                                    <option value="#6熱轉印" x-show="item.category === 'child'">#6熱轉印</option>
                                                    <option value="#8" x-show="item.category === 'child'">#8</option>
                                                    <option value="#10" x-show="item.category === 'child'">#10</option>
                                                    <option value="XS" x-show="item.category === 'adult'">XS</option>
                                                    <option value="S" x-show="item.category === 'adult'">S</option>
                                                    <option value="M" x-show="item.category === 'adult'">M</option>
                                                    <option value="L" x-show="item.category === 'adult'">L</option>
                                                    <option value="XL" x-show="item.category === 'adult'">XL</option>
                                                    <option value="2L" x-show="item.category === 'adult'">2L</option>
                                                    <option value="3L" x-show="item.category === 'adult'">3L</option>
                                                    <option value="5L" x-show="item.category === 'adult'">5L</option>
                                                </select>
                                            </label>

                                            <label class="block">
                                                <span class="text-sm font-bold text-slate-700">數量</span>
                                                <input class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" type="number" min="1" max="99" x-model.number="item.quantity">
                                            </label>

                                            <button class="self-end rounded-lg border border-red-200 px-3 py-2 text-sm font-black text-red-600 hover:bg-red-50" type="button" @click="removeItem(index)">
                                                刪除
                                            </button>
                                        </article>
                                    </template>
                                </div>
                            @endif

                            <div class="mt-5 overflow-hidden rounded-xl border border-slate-200">
                                <table class="w-full border-collapse text-left text-sm">
                                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                                        <tr>
                                            <th class="px-4 py-3" style="background-color: rgb(201 211 221); border-bottom: 1px solid rgba(120, 140, 160, 0.35);">類別</th>
                                            <th class="px-4 py-3" style="background-color: rgb(201 211 221); border-bottom: 1px solid rgba(120, 140, 160, 0.35);">尺寸</th>
                                            <th class="px-4 py-3 text-right" style="background-color: rgb(201 211 221); border-bottom: 1px solid rgba(120, 140, 160, 0.35);">數量</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($classShirtOrder)
                                            @forelse ($classShirtOrder->items ?? [] as $item)
                                                <tr>
                                                    <td class="px-4 py-3 font-bold text-slate-800" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                        {{ ClassShirtOrder::categoryLabel($item['category'] ?? '') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-700" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                        {{ ClassShirtOrder::normalizeSize($item['size'] ?? '') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-slate-700" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                        {{ $item['quantity'] ?? 0 }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="px-4 py-5 text-center font-bold text-slate-500" colspan="3" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                        尚未新增訂購內容。
                                                    </td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <template x-if="items.length === 0">
                                                <tr>
                                                    <td class="px-4 py-5 text-center font-bold text-slate-500" colspan="3" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                        尚未新增訂購內容。
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="(item, index) in items" :key="'summary-' + index">
                                                <tr>
                                                    <td class="px-4 py-3 font-bold text-slate-800" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);" x-text="categoryLabels[item.category] ?? item.category"></td>
                                                    <td class="px-4 py-3 text-slate-700" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);" x-text="item.size"></td>
                                                    <td class="px-4 py-3 text-right text-slate-700" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);" x-text="item.quantity"></td>
                                                </tr>
                                            </template>
                                        @endif
                                    </tbody>
                                    <tfoot class="font-black text-kiwi-ink">
                                        <tr>
                                            <td class="px-4 py-3" colspan="2" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">總件數</td>
                                            <td class="px-4 py-3 text-right" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                @if ($classShirtOrder)
                                                    {{ $classShirtOrder->totalQuantity() }} 件
                                                @else
                                                    <span x-text="totalQuantity()"></span> 件
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-3" colspan="2" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">總金額</td>
                                            <td class="px-4 py-3 text-right" style="border-bottom: 1px solid rgba(120, 140, 160, 0.35);">
                                                @if ($classShirtOrder)
                                                    NT$ {{ number_format($classShirtOrder->totalAmount()) }}
                                                @else
                                                    <span x-text="formatCurrency(totalAmount())"></span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-5 grid items-stretch gap-4 lg:grid-cols-2">
                                <div class="rounded-xl border border-[#c9d3dd] bg-transparent p-5">
                                    <p class="pb-3 text-sm font-black text-slate-700">匯款資訊</p>
                                    <div class="space-y-1.5 text-base font-bold leading-relaxed text-kiwi-ink">
                                        <p><span class="text-slate-500">銀行：</span>中國信託 822</p>
                                        <p><span class="text-slate-500">帳號：</span>647540040132</p>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-[#c9d3dd] bg-transparent p-5">
                                    @if ($classShirtOrder)
                                        <form
                                            class="flex h-full flex-col"
                                            method="POST"
                                            action="{{ route('member.class-shirt-order.payment.update') }}"
                                            x-data="{ paymentMethod: @js(old('payment_method', $classShirtOrder->payment_method)) }"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <label class="block">
                                                <span class="block pb-3 text-sm font-bold text-slate-700">付款方式</span>
                                                <select class="w-full rounded-lg border-slate-300 bg-white focus:border-kiwi-blue focus:ring-kiwi-blue" name="payment_method" x-model="paymentMethod">
                                                    @foreach (ClassShirtOrder::PAYMENT_METHOD_LABELS as $value => $label)
                                                        <option value="{{ $value }}" @selected(old('payment_method', $classShirtOrder->payment_method) === $value)>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </label>

                                            <div class="mt-5" x-show="paymentMethod === 'transfer'">
                                                <label class="block" x-show="paymentMethod === 'transfer'">
                                                    <span class="block pb-3 text-sm font-bold text-slate-700">帳號末五碼</span>
                                                    <input
                                                        class="w-full rounded-lg border-slate-300 bg-white focus:border-kiwi-blue focus:ring-kiwi-blue"
                                                        type="text"
                                                        name="payment_account_last_five"
                                                        value="{{ old('payment_account_last_five', $classShirtOrder->payment_account_last_five) }}"
                                                        inputmode="numeric"
                                                        maxlength="5"
                                                        placeholder="匯款請填 5 碼"
                                                    >
                                                </label>
                                            </div>

                                            <div class="mt-5">
                                                <p class="pb-3 text-sm font-bold text-slate-700">付款狀態</p>
                                                <p class="inline-flex rounded-full border px-3 py-1 text-sm font-black text-kiwi-ink" style="{{ $paymentStatusBadgeStyle }}">
                                                    {{ $classShirtOrder->payment_status_label }}
                                                </p>
                                            </div>

                                            <div class="mt-8 flex justify-end">
                                                <button class="rounded-lg bg-kiwi-blue px-5 py-3 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">
                                                    更新付款資訊
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <template x-if="submittedAt">
                                            <dl class="space-y-3 text-sm">
                                                <div>
                                                    <dt class="font-black text-slate-500">付款方式</dt>
                                                    <dd class="mt-1 font-bold text-slate-800" x-text="paymentMethodLabels[paymentMethod] ?? '-'"></dd>
                                                </div>
                                                <div>
                                                    <dt class="font-black text-slate-500">帳號末五碼</dt>
                                                    <dd class="mt-1 font-bold text-slate-800" x-text="paymentAccountLastFive || '-'"></dd>
                                                </div>
                                                <div>
                                                    <dt class="font-black text-slate-500">付款狀態</dt>
                                                    <dd
                                                        class="mt-1 inline-flex rounded-full border px-3 py-1 font-black text-kiwi-ink"
                                                        style="background-color: rgb(254 243 199); border-color: rgb(252 211 77);"
                                                        x-text="paymentStatusLabel"
                                                    ></dd>
                                                </div>
                                            </dl>
                                        </template>

                                        <div class="space-y-4" x-show="! submittedAt">
                                            <label class="block">
                                                <span class="text-sm font-bold text-slate-700">付款方式</span>
                                                <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" x-model="paymentMethod">
                                                    <option value="cash">現金</option>
                                                    <option value="transfer">匯款</option>
                                                </select>
                                            </label>

                                            <label class="block" x-show="paymentMethod === 'transfer'">
                                                <span class="text-sm font-bold text-slate-700">帳號末五碼</span>
                                                <input
                                                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                                                    type="text"
                                                    inputmode="numeric"
                                                    maxlength="5"
                                                    placeholder="匯款請填 5 碼"
                                                    x-model="paymentAccountLastFive"
                                                >
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if (! $classShirtOrder)
                                <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between" x-show="! submittedAt">
                                    <p class="text-sm font-bold text-slate-500">完成送出後，前台不可自行修改訂購內容。</p>

                                    <button class="rounded-lg bg-kiwi-blue px-5 py-3 text-sm font-black text-white hover:bg-kiwi-ink disabled:cursor-not-allowed disabled:opacity-60" type="button" @click="submit()" :disabled="isSaving">
                                        <span x-show="! isSaving">送出班服訂單</span>
                                        <span x-show="isSaving">送出中...</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('member.logout') }}" class="mt-6">
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
