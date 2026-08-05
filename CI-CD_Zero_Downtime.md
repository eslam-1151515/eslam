# نظام النشر بدون توقف (Zero-Downtime Deployment)

## الفكرة الأساسية
تم تجهيز هذا المشروع بنظام CI/CD عبر GitHub Actions لرفع التحديثات إلى خادم (CyberPanel / OpenLiteSpeed) بدون أي توقف (Zero-Downtime). 
يعتمد النظام على إنشاء مجلد جديد لكل تحديث (Release)، ثم تبديل المسار الرئيسي (Symlink) إليه في كسر من الثانية بمجرد نجاح بناء المشروع (npm build & composer install).

## البنية التحتية (Server Structure)
- **المسار الرئيسي للموقع (Document Root):** `/home/fast-order-eg.tech/deploy/current/public`
- **مجلد التحديثات (Releases):** `/home/fast-order-eg.tech/deploy/releases/` (يحتوي على النسخ برقم التاريخ)
- **المجلد المشترك (Shared):** `/home/fast-order-eg.tech/deploy/shared/` (يحتوي على `.env` ومجلد `storage` ليظل ثابتاً ولا يُمحى مع كل تحديث)

## ملفات التشغيل (Deployment Scripts)
- **`zero_downtime_deploy.sh`**: هو السكريبت الأساسي الذي يعمل على السيرفر، يقوم بتحميل الكود من جيت هاب، ربط الملفات المشتركة، تنفيذ الأوامر، وتحديث مسار `current`.
- **`.github/workflows/deploy.yml`**: الملف الذي يوجه GitHub Actions للاتصال بالسيرفر عبر SSH وتشغيل السكريبت.

## ملاحظات لأي مطور أو ذكاء اصطناعي (AI Context)
- **لا تقم أبداً** بتغيير مسار السيرفر إلى `public_html`، ولا تستخدم `php artisan down` أثناء التحديثات، النظام مصمم ليعمل في الخلفية بدون توقف نهائياً.
- قاعدة البيانات تعمل واسمها `fastorder_db`.
- يتم استخدام `GITHUB_TOKEN` الافتراضي الموفر من GitHub Actions في عمليات الـ git clone داخل السيرفر لضمان الأمان وعدم تسريب أي Token شخصي.
- شهادة الأمان (Wildcard SSL) مثبتة على الدومين الرئيسي والدومينات الفرعية (Subdomains).
