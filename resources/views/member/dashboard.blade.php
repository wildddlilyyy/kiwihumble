<x-layouts.app title="Member Home">
    <main class="kiwi-grid-bg min-h-screen px-6 py-10 text-white">
        <section class="mx-auto flex min-h-screen max-w-5xl items-center">
            <div class="w-full">
                <div class="mx-auto w-40">
                    <x-kiwi-loader />
                </div>

                <div
                    class="mt-8 rounded-xl bg-white/95 p-6 text-kiwi-ink shadow-xl shadow-black/10 ring-1 ring-white/40"
                    x-data="{ tab: new URLSearchParams(window.location.search).get('tab') === 'class-shirt' ? 'class-shirt' : 'profile', addOpen: false, editing: null }"
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
                            個人資訊
                        </button>
                        <button
                            class="rounded-full px-4 py-2 text-sm font-black transition"
                            :class="tab === 'class-shirt' ? 'bg-kiwi-blue text-white' : 'bg-slate-100 text-kiwi-blue hover:bg-slate-200'"
                            type="button"
                            @click="tab = 'class-shirt'"
                        >
                            班服訂購登記
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
                            alt="Humble 校慶版班服示意圖"
                        >

                        <div class="grid gap-4 lg:grid-cols-2">
                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">吸濕排汗 - 兒童 尺寸表/SIZE</div>
                                <table class="w-full text-center text-sm">
                                    <thead class="bg-slate-50 text-kiwi-blue">
                                        <tr><th class="px-3 py-2"></th><th>#6</th><th>#8</th><th>#10</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 font-bold">
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">衣長</th><td>46</td><td>50</td><td>54</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">胸寬</th><td>34</td><td>37</td><td>40</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">袖長</th><td>14</td><td>15</td><td>16</td></tr>
                                        <tr><th class="px-3 py-2 text-left text-kiwi-blue">肩寬</th><td>31</td><td>33</td><td>35</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="overflow-hidden rounded-xl border border-slate-200">
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">吸濕排汗 - 大人 尺寸表/SIZE（單位/cm）</div>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[620px] text-center text-sm">
                                        <thead class="bg-slate-50 text-kiwi-blue">
                                            <tr><th class="px-3 py-2"></th><th>XS</th><th>S</th><th>M</th><th>L</th><th>XL</th><th>2L</th><th>3L</th><th>5L</th></tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200 font-bold">
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">衣長</th><td>60</td><td>64</td><td>68</td><td>70</td><td>72</td><td>76</td><td>80</td><td>84</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">胸寬</th><td>43</td><td>46</td><td>50</td><td>53</td><td>56</td><td>60</td><td>64</td><td>72</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">袖長</th><td>18</td><td>19</td><td>20</td><td>21</td><td>22</td><td>24</td><td>26</td><td>27</td></tr>
                                            <tr><th class="px-3 py-2 text-left text-kiwi-blue">肩寬</th><td>38</td><td>40</td><td>42</td><td>44</td><td>46</td><td>48</td><td>50</td><td>55</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm font-bold text-slate-500">* 平放尺寸丈量，尺寸容許範圍 +-2.5cm 皆為正常值。</p>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <h2 class="text-xl font-black text-kiwi-ink">我的班服訂購</h2>
                                <button class="grid size-11 place-items-center rounded-full bg-kiwi-blue text-2xl font-black text-white hover:bg-kiwi-ink" type="button" @click="addOpen = ! addOpen; editing = null">
                                    +
                                </button>
                            </div>

                            <form method="POST" action="{{ route('member.class-shirt-orders.store') }}" class="mt-4 grid gap-4 rounded-lg bg-slate-50 p-4 sm:grid-cols-[1fr_1fr_120px_auto]" x-show="addOpen">
                                @csrf
                                @include('member.partials.class-shirt-order-fields')
                                <button class="self-end rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">確定</button>
                            </form>

                            <div class="mt-4 space-y-3">
                                @forelse ($member->classShirtOrders as $order)
                                    <article class="rounded-lg border border-slate-200 p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="font-black text-kiwi-ink">{{ $order->categoryLabel() }} / {{ $order->size }} / {{ $order->quantity }} 件</p>
                                                <p class="mt-1 text-sm font-bold text-slate-500">
                                                    最後送出：{{ $order->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') }}
                                                </p>
                                            </div>
                                            <div class="flex gap-2">
                                                <button class="rounded-lg border border-kiwi-blue px-3 py-2 text-sm font-black text-kiwi-blue hover:bg-slate-50" type="button" @click="editing = editing === {{ $order->id }} ? null : {{ $order->id }}; addOpen = false">
                                                    修改
                                                </button>
                                                <form method="POST" action="{{ route('member.class-shirt-orders.destroy', $order) }}" onsubmit="return confirm('確定刪除這筆班服訂購嗎？')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-black text-red-600 hover:bg-red-50" type="submit">
                                                        刪除
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('member.class-shirt-orders.update', $order) }}" class="mt-4 grid gap-4 rounded-lg bg-slate-50 p-4 sm:grid-cols-[1fr_1fr_120px_auto]" x-show="editing === {{ $order->id }}">
                                            @csrf
                                            @method('PUT')
                                            @include('member.partials.class-shirt-order-fields', ['order' => $order])
                                            <button class="self-end rounded-lg bg-kiwi-blue px-4 py-2 text-sm font-black text-white hover:bg-kiwi-ink" type="submit">確定</button>
                                        </form>
                                    </article>
                                @empty
                                    <div class="rounded-lg bg-slate-50 p-6 text-center font-bold text-slate-500">
                                        尚未新增班服訂購。
                                    </div>
                                @endforelse
                            </div>
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
