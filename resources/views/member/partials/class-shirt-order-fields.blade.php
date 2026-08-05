@php
    $currentCategory = old('category', $order->category ?? 'child');
    $currentSize = old('size', $order->size ?? '#6');
@endphp

<label class="block">
    <span class="text-sm font-bold text-slate-700">類別</span>
    <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" name="category" required>
        <option value="child" @selected($currentCategory === 'child')>兒童</option>
        <option value="adult" @selected($currentCategory === 'adult')>大人</option>
    </select>
</label>

<label class="block">
    <span class="text-sm font-bold text-slate-700">尺寸</span>
    <select class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue" name="size" required>
        <optgroup label="兒童">
            @foreach (['#6', '#8', '#10'] as $size)
                <option value="{{ $size }}" @selected($currentSize === $size)>{{ $size }}</option>
            @endforeach
        </optgroup>
        <optgroup label="大人">
            @foreach (['XS', 'S', 'M', 'L', 'XL', '2L', '3L', '5L'] as $size)
                <option value="{{ $size }}" @selected($currentSize === $size)>{{ $size }}</option>
            @endforeach
        </optgroup>
    </select>
</label>

<label class="block">
    <span class="text-sm font-bold text-slate-700">數量</span>
    <input
        class="mt-2 w-full rounded-lg border-slate-300 focus:border-kiwi-blue focus:ring-kiwi-blue"
        type="number"
        name="quantity"
        value="{{ old('quantity', $order->quantity ?? 1) }}"
        min="1"
        max="99"
        required
    >
</label>
