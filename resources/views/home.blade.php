<x-layouts.app :title="$title">
    <main
        class="kiwi-grid-bg min-h-screen overflow-hidden bg-kiwi-blue text-white"
        data-countdown
        data-target-date="{{ $tripDate }}T00:00:00+08:00"
        data-timezone="{{ $timezone }}"
    >
        <section class="grid min-h-screen place-items-center px-6 py-10">
            <div class="w-full max-w-5xl text-center">
                <div class="mx-auto w-[min(68vw,320px)] sm:w-[min(46vw,380px)]">
                    <x-kiwi-loader />
                </div>

                <p class="mt-8 font-hand text-xl text-kiwi-gold sm:text-2xl">2027 / 5 / 29</p>
                <h1 class="mx-auto mt-4 max-w-4xl text-balance font-display text-4xl font-extrabold leading-none tracking-normal sm:text-6xl lg:text-7xl">
                    {{ $title }}
                </h1>

                <div class="mx-auto mt-8 grid max-w-3xl grid-cols-2 gap-3 sm:grid-cols-4" aria-label="Countdown to graduation trip">
                    <div class="count-card">
                        <span class="count-value" data-count-days>000</span>
                        <span class="count-label">Days</span>
                    </div>
                    <div class="count-card">
                        <span class="count-value" data-count-hours>00</span>
                        <span class="count-label">Hours</span>
                    </div>
                    <div class="count-card">
                        <span class="count-value" data-count-minutes>00</span>
                        <span class="count-label">Minutes</span>
                    </div>
                    <div class="count-card">
                        <span class="count-value" data-count-seconds>00</span>
                        <span class="count-label">Seconds</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
