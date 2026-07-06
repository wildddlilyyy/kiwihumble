@csrf

<div class="grid gap-5">
    <div class="grid gap-5 sm:grid-cols-2">
        <label class="block">
            <span class="text-sm font-bold text-slate-700">Name</span>
            <input
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                type="text"
                name="name"
                value="{{ old('name', $member->name ?? '') }}"
                required
            >
        </label>

        <label class="block">
            <span class="text-sm font-bold text-slate-700">Birthday</span>
            <input
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                type="date"
                name="birthday"
                value="{{ old('birthday', isset($member) ? $member->birthday?->format('Y-m-d') : '') }}"
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
                value="{{ old('mom_name', $member->mom_name ?? '') }}"
            >
        </label>

        <label class="block">
            <span class="text-sm font-bold text-slate-700">Mom Phone</span>
            <input
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                type="text"
                name="mom_phone"
                value="{{ old('mom_phone', $member->mom_phone ?? '') }}"
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
                value="{{ old('dad_name', $member->dad_name ?? '') }}"
            >
        </label>

        <label class="block">
            <span class="text-sm font-bold text-slate-700">Dad Phone</span>
            <input
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                type="text"
                name="dad_phone"
                value="{{ old('dad_phone', $member->dad_phone ?? '') }}"
            >
        </label>
    </div>

    @if (isset($member))
        <div class="rounded-lg bg-amber-50 p-4 text-sm font-bold text-amber-800">
            Login password: <span class="font-black">{{ $member->login_password ?: 'Not recorded yet' }}</span>
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-bold text-slate-700">Password</span>
                <div class="mt-2 flex rounded-lg border border-slate-300 bg-white focus-within:border-kiwi-blue focus-within:ring-1 focus-within:ring-kiwi-blue">
                    <input
                        id="member-create-password"
                        class="w-full rounded-l-lg border-0 focus:ring-0"
                        type="password"
                        name="password"
                        required
                    >
                    <button class="px-3 text-sm font-black text-kiwi-blue" type="button" data-password-toggle="#member-create-password" aria-pressed="false">
                        Show
                    </button>
                </div>
            </label>

            <label class="block">
                <span class="text-sm font-bold text-slate-700">Confirm Password</span>
                <div class="mt-2 flex rounded-lg border border-slate-300 bg-white focus-within:border-kiwi-blue focus-within:ring-1 focus-within:ring-kiwi-blue">
                    <input
                        id="member-create-password-confirmation"
                        class="w-full rounded-l-lg border-0 focus:ring-0"
                        type="password"
                        name="password_confirmation"
                        required
                    >
                    <button class="px-3 text-sm font-black text-kiwi-blue" type="button" data-password-toggle="#member-create-password-confirmation" aria-pressed="false">
                        Show
                    </button>
                </div>
            </label>
        </div>
    @endif
</div>
