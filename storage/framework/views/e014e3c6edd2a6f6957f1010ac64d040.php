<?php $__env->startSection('title', 'اتصل بنا | فاست أوردر (Fast Order) - دعم فني متواصل 24/7'); ?>

<?php $__env->startSection('meta_description', 'تواصل مع فريق الدعم الفني أو المبيعات في فاست أوردر. نحن هنا لمساعدتك في الإجابة على استفساراتك وتوسيع نطاق مبيعات متجرك.'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-16 md:py-24 relative overflow-hidden bg-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="px-4 py-1.5 rounded-full bg-pink-500/10 text-pink-400 text-xs font-bold border border-pink-500/20 inline-block uppercase tracking-wider mb-4">
                📞 تواصل معنا
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">
                يسعدنا دائماً سماع <span class="text-gradient-primary">استفساراتك ورأيك</span>
            </h1>
            <p class="text-gray-400 mt-4 leading-relaxed">
                هل لديك استفسار عن الباقات؟ أو بحاجة لمساعدة تقنية؟ املأ النموذج أدناه وسيقوم فريق الدعم بالرد عليك في أسرع وقت ممكن.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Contact Info Sidebar (5 cols) -->
            <div class="lg:col-span-5 space-y-8">
                
                <div class="glass-card p-8 rounded-2xl border-white/5 space-y-6">
                    <h3 class="text-xl font-bold text-white">معلومات الاتصال المباشر</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-brand-500/30 transition-all">
                            <div class="w-11 h-11 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">رقم الهاتف والدعم</div>
                                <div class="font-bold text-white font-mono mt-0.5">01146520922</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-pink-500/30 transition-all">
                            <div class="w-11 h-11 rounded-xl bg-pink-500/20 text-pink-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">البريد الإلكتروني للدعم</div>
                                <div class="font-bold text-white font-mono mt-0.5 text-sm">support@fastorder.test</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-amber-500/30 transition-all">
                            <div class="w-11 h-11 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-business-time"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">المبيعات والشراكات</div>
                                <div class="font-bold text-white font-mono mt-0.5 text-sm">sales@fastorder.test</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Chat Card -->
                <div class="glass-card p-8 rounded-2xl border-emerald-500/20 bg-emerald-950/10 text-center space-y-4">
                    <div class="w-14 h-14 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto">
                        <i class="fa-brands fa-whatsapp text-3xl"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white">دردشة فورية عبر واتساب</h4>
                    <p class="text-gray-400 text-sm">تواصل مع خدمة العملاء مباشرة لحل استفسارك في ثوانٍ معدودة.</p>
                    <a href="https://wa.me/201146520922" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-white bg-emerald-600 hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/25">
                        <span>ابدأ المحادثة الآن</span>
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </a>
                </div>

            </div>

            <!-- Contact Form Card (7 cols) -->
            <div class="lg:col-span-7">
                <div class="glass-card p-8 sm:p-10 rounded-2xl border-white/5 relative overflow-hidden">
                    <div class="absolute -top-12 -left-12 w-48 h-48 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <?php if(session('success')): ?>
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-semibold flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-lg"></i>
                            <span><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('main.contact.submit')); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-300 mb-2">الاسم الكامل</label>
                                <input type="text" name="name" id="name" required class="w-full px-4 py-3 rounded-xl bg-dark-bg border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500/60 transition-all" placeholder="أحمد محمد">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-xs text-rose-500 mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-300 mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" id="email" required class="w-full px-4 py-3 rounded-xl bg-dark-bg border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500/60 transition-all font-mono text-left" placeholder="example@mail.com">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-xs text-rose-500 mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-300 mb-2">رقم الهاتف (اختياري)</label>
                                <input type="tel" name="phone" id="phone" class="w-full px-4 py-3 rounded-xl bg-dark-bg border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500/60 transition-all font-mono text-left" placeholder="01146520922">
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-xs text-rose-500 mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-bold text-gray-300 mb-2">الموضوع</label>
                                <select name="subject" id="subject" required class="w-full px-4 py-3 rounded-xl bg-dark-bg border border-white/10 text-white focus:outline-none focus:border-brand-500/60 transition-all">
                                    <option value="support" class="bg-dark-card text-white">دعم فني واستفسار تقني</option>
                                    <option value="sales" class="bg-dark-card text-white">استفسار عن المبيعات والباقات</option>
                                    <option value="custom" class="bg-dark-card text-white">طلب حل مخصص / شركات كبرى</option>
                                    <option value="other" class="bg-dark-card text-white">أخرى</option>
                                </select>
                                <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-xs text-rose-500 mt-1 block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-bold text-gray-300 mb-2">نص الرسالة</label>
                            <textarea name="message" id="message" rows="5" required class="w-full px-4 py-3 rounded-xl bg-dark-bg border border-white/10 text-white placeholder-gray-500 focus:outline-none focus:border-brand-500/60 transition-all" placeholder="اكتب تفاصيل استفسارك هنا..."></textarea>
                            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-xs text-rose-500 mt-1 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl font-bold text-white bg-gradient-to-r from-brand-600 via-indigo-600 to-pink-600 hover:from-brand-500 hover:to-pink-500 shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>إرسال الرسالة الآن</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.platform', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\programing\flutter project\fast order\resources\views\platform\contact.blade.php ENDPATH**/ ?>