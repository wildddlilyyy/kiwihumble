<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => ['title' => $title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($title)]); ?>
    <main
        class="min-h-screen overflow-hidden bg-kiwi-blue text-white"
        data-countdown
        data-target-date="<?php echo e($tripDate); ?>T00:00:00+08:00"
        data-timezone="<?php echo e($timezone); ?>"
    >
        <section class="grid min-h-screen place-items-center px-6 py-10">
            <div class="w-full max-w-5xl text-center">
                <div class="mx-auto w-[min(68vw,320px)] sm:w-[min(46vw,380px)]">
                    <?php if (isset($component)) { $__componentOriginal391b701f05f3085c3ed37a88e2b49367 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal391b701f05f3085c3ed37a88e2b49367 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.kiwi-loader','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('kiwi-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal391b701f05f3085c3ed37a88e2b49367)): ?>
<?php $attributes = $__attributesOriginal391b701f05f3085c3ed37a88e2b49367; ?>
<?php unset($__attributesOriginal391b701f05f3085c3ed37a88e2b49367); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal391b701f05f3085c3ed37a88e2b49367)): ?>
<?php $component = $__componentOriginal391b701f05f3085c3ed37a88e2b49367; ?>
<?php unset($__componentOriginal391b701f05f3085c3ed37a88e2b49367); ?>
<?php endif; ?>
                </div>

                <p class="mt-8 text-sm font-black uppercase tracking-[0.28em] text-kiwi-gold">2027 / 5 / 29</p>
                <h1 class="mx-auto mt-4 max-w-4xl text-balance text-4xl font-black leading-none tracking-normal sm:text-6xl lg:text-7xl">
                    <?php echo e($title); ?>

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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH C:\Users\User\Documents\KiwiHumble\resources\views/home.blade.php ENDPATH**/ ?>