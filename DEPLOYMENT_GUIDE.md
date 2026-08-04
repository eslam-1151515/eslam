# دليل رفع مشروع Kim Trading على FastPanel

## الخطوات المطلوبة للرفع:

### 1. إعداد قاعدة البيانات
- أنشئ قاعدة بيانات MySQL جديدة في FastPanel
- احتفظ ببيانات الاتصال (اسم قاعدة البيانات، اسم المستخدم، كلمة المرور)

### 2. تعديل ملف .env
- انسخ محتوى ملف `.env.production` إلى `.env` الجديد
- غير المتغيرات التالية فقط:
  ```
  DB_DATABASE=your_actual_database_name
  DB_USERNAME=your_actual_database_username
  DB_PASSWORD=your_actual_database_password
  ```
  
**ملاحظة**: APP_URL و SESSION_DOMAIN محدثين بالفعل لـ kim.bird-ads.com

### 3. رفع الملفات
- ارفع جميع ملفات المشروع عدا:
  - node_modules/
  - .git/
  - storage/logs/*
  - .env (سيتم إنشاؤه يدوياً)

### 4. إعداد المجلدات والأذونات
```bash
# إنشاء الروابط المطلوبة
php artisan storage:link

# ضبط أذونات المجلدات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 public/
```

### 5. تشغيل أوامر Laravel
```bash
# تثبيت التبعيات
composer install --optimize-autoloader --no-dev

# إنشاء مفتاح التطبيق
php artisan key:generate

# تشغيل المايجريشن
php artisan migrate --force

# مسح وإعادة بناء التخزين المؤقت
php artisan config:cache
php artisan route:cache
php artisan view:cache

# بناء الأصول
npm install
npm run build
```

### 6. إعداد اختياري - البيانات التجريبية
```bash
# تشغيل السيدر (اختياري)
php artisan db:seed
```

### 7. اختبار التطبيق
- تأكد من عمل الروابط:
  - https://yourdomain.com (الصفحة الرئيسية)
  - https://yourdomain.com/shop/ (المتجر)
  - https://yourdomain.com/login (لوحة التحكم)

## ملفات مهمة تم إنشاؤها:
- `.env.production` - إعدادات الإنتاج
- `.htaccess` - إعادة توجيه Apache
- `public/.htaccess` - محدث بإعدادات الأمان
- `vite.config.js` - محدث للإنتاج

## استرداد في حالة المشاكل:
```bash
# مسح التخزين المؤقت
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# إعادة بناء
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## معلومات مهمة:
- تأكد من PHP 8.1+ و MySQL 5.7+
- تأكد من تمكين mod_rewrite في Apache
- تأكد من كون document root يشير للمجلد الرئيسي وليس public/
- الأمان: ملف .env محمي من الوصول المباشر