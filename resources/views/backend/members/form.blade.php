@csrf

<div class="grid gap-5">
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
        <span class="text-sm font-bold text-slate-700">Phone</span>
        <input
            class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
            type="text"
            name="phone"
            value="{{ old('phone', $member->phone ?? '') }}"
        >
    </label>

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
            <span class="text-sm font-bold text-slate-700">Dad</span>
            <input
                class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                type="text"
                name="dad_name"
                value="{{ old('dad_name', $member->dad_name ?? '') }}"
            >
        </label>
    </div>

    @if (! isset($member))
        <div class="grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-bold text-slate-700">Password</span>
                <input
                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                    type="password"
                    name="password"
                    required
                >
            </label>

            <label class="block">
                <span class="text-sm font-bold text-slate-700">Confirm Password</span>
                <input
                    class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
                    type="password"
                    name="password_confirmation"
                    required
                >
            </label>
        </div>
    @endif
</div>
