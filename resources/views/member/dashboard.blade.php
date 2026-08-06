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
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">吸濕排汗 - 兒童 尺寸表 / SIZE</div>
                                <table class="w-full text-center text-sm">
                                    <thead class="bg-sky-100 text-kiwi-blue">
                                        <tr class="h-12"><th class="px-3 py-3"></th><th class="px-3 py-3">#6</th><th class="px-3 py-3">#8</th><th class="px-3 py-3">#10</th></tr>
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
                                <div class="bg-kiwi-ink px-4 py-2 font-black text-kiwi-gold">吸濕排汗 - 大人 尺寸表 / SIZE（單位 / cm）</div>
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[620px] text-center text-sm">
                                        <thead class="bg-sky-100 text-kiwi-blue">
                                            <tr class="h-12"><th class="px-3 py-3"></th><th class="px-3 py-3">XS</th><th class="px-3 py-3">S</th><th class="px-3 py-3">M</th><th class="px-3 py-3">L</th><th class="px-3 py-3">XL</th><th class="px-3 py-3">2L</th><th class="px-3 py-3">3L</th><th class="px-3 py-3">5L</th></tr>
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

                        <div
                            class="rounded-xl border border-slate-200 p-4"
                            x-data="classShirtOrderForm({
                                items: @js($member->classShirtOrder?->items ?? []),
                                submittedAt: @js($member->classShirtOrder?->submitted_at?->timezone(config('app.timezone'))->format('Y-m-d H:i')),
                                storeUrl: @js(route('member.class-shirt-order.store')),
                                csrfToken: @js(csrf_token()),
                            })"
                        >
                            <div class="flex items-center justify-between gap-4">
                                <h2 class="text-xl font-black text-kiwi-ink">班服訂購內容</h2>
                                <button class="grid size-11 place-items-center rounded-full bg-kiwi-blue text-2xl font-black text-white hover:bg-kiwi-ink" type="button" @click="addItem()">
                                    +
                                </button>
                            </div>

                            <div class="mt-4 rounded-lg bg-emerald-50 p-3 text-sm font-bold text-emerald-700" x-show="status" x-text="status"></div>
                            <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm font-bold text-red-700" x-show="error" x-text="error"></div>

                            <div class="mt-4 space-y-3">
                                <template x-if="items.length === 0">
                                    <div class="rounded-lg bg-slate-50 p-6 text-center font-bold text-slate-500">
                                        尚未新增班服訂購。
                                    </div>
                                </template>

                                <template x-for="(item, index) in items" :key="index">
                                    <article class="grid gap-4 rounded-lg border border-slate-200 p-4 sm:grid-cols-[1fr_1fr_120px_auto]">
                                        <label class="block">
                                            <span class="text-sm font-bold text-slate-700">類別</span>
                                            <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" x-model="item.category" @change="normalizeSize(item)">
                                                <option value="child">兒童</option>
                                                <option value="adult">大人</option>
                                            </select>
                                        </label>

                                        <label class="block">
                                            <span class="text-sm font-bold text-slate-700">尺寸</span>
                                            <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" x-model="item.size">
                                                <option value="#6" x-show="item.category === 'child'">#6</option>
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

                            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <p class="text-sm font-bold text-slate-500">
                                    總數量：<span class="text-kiwi-blue" x-text="totalQuantity()"></span>
                                    <template x-if="submittedAt">
                                        <span>，最後送出：<span x-text="submittedAt"></span></span>
                                    </template>
                                </p>

                                <button class="rounded-lg bg-kiwi-blue px-5 py-3 text-sm font-black text-white hover:bg-kiwi-ink disabled:cursor-not-allowed disabled:opacity-60" type="button" @click="submit()" :disabled="isSaving">
                                    <span x-show="! isSaving">送出班服訂單</span>
                                    <span x-show="isSaving">送出中...</span>
                                </button>
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
