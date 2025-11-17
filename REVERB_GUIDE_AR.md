# دليل شامل لاستخدام Laravel Reverb

## ما هو Laravel Reverb؟

Laravel Reverb هو خادم WebSocket مدمج في Laravel يسمح لك بإرسال رسائل فورية (Real-time) من السيرفر إلى المتصفحات بدون الحاجة لخدمات خارجية مثل Pusher.

---

## الخطوة 1: إعداد متغيرات البيئة (.env)

أضف هذه المتغيرات في ملف `.env` الخاص بك:

```env
# Broadcasting Configuration
BROADCAST_CONNECTION=reverb

# Reverb Server Configuration
REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Reverb Server Settings (للخادم نفسه)
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
```

### كيفية إنشاء المفاتيح (Keys):

افتح Terminal واكتب:

```bash
php artisan reverb:install
```

هذا الأمر سيقوم بـ:
1. إنشاء المفاتيح تلقائياً
2. إضافتها إلى ملف `.env`
3. إعداد كل شيء بشكل تلقائي

أو يمكنك إنشاء المفاتيح يدوياً:

```bash
php artisan reverb:generate-app-key
```

---

## الخطوة 2: إعداد متغيرات Vite (للـ Frontend)

في ملف `.env` أضف أيضاً:

```env
# Vite Environment Variables (للـ Frontend)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**ملاحظة مهمة:** متغيرات `VITE_*` يجب أن تبدأ بـ `VITE_` حتى يتمكن Vite من قراءتها في المتصفح.

---

## الخطوة 3: تشغيل خادم Reverb

افتح Terminal جديد واكتب:

```bash
php artisan reverb:start
```

سترى رسالة مثل:
```
Starting Reverb server on 0.0.0.0:8080...
```

**احتفظ بهذا Terminal مفتوحاً** - خادم Reverb يجب أن يعمل دائماً.

---

## الخطوة 4: إعداد الـ Event (الحدث)

### مثال: SystemMaintenanceEvent

الملف `app/Events/SystemMaintenanceEvent.php` يجب أن:

1. **يستخدم `ShouldBroadcast` interface**
2. **يحتوي على دالة `broadcastOn()`** لتحديد القناة
3. **يمكن إضافة دالة `broadcastWith()`** لإرسال بيانات مع الحدث

```php
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemMaintenanceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $status;

    public function __construct($message, $status = 'info')
    {
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * تحديد القناة التي سيتم البث عليها
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('system-maintenance'), // قناة عامة
            // أو
            // new PrivateChannel('system-maintenance'), // قناة خاصة (تتطلب مصادقة)
        ];
    }

    /**
     * البيانات التي سيتم إرسالها مع الحدث
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * اسم الحدث (اختياري - افتراضي: اسم الكلاس)
     */
    public function broadcastAs(): string
    {
        return 'maintenance.update';
    }
}
```

---

## الخطوة 5: إرسال الحدث (Broadcasting)

### من Controller أو أي مكان في الكود:

```php
use App\Events\SystemMaintenanceEvent;

// إرسال الحدث
event(new SystemMaintenanceEvent('System will be under maintenance in 10 minutes', 'warning'));

// أو
broadcast(new SystemMaintenanceEvent('Maintenance started', 'error'));
```

### الفرق بين `event()` و `broadcast()`:

- **`event()`**: يرسل الحدث محلياً فقط (في نفس السيرفر)
- **`broadcast()`**: يرسل الحدث عبر WebSocket إلى جميع المتصفحات المتصلة

---

## الخطوة 6: الاستماع للحدث في Frontend (JavaScript)

### في Blade Template:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>System Maintenance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="maintenance-alert"></div>

    <script>
        // الاستماع للقناة العامة
        Echo.channel('system-maintenance')
            .listen('.maintenance.update', (e) => {
                console.log('Maintenance Event:', e);
                
                // عرض التنبيه
                const alertDiv = document.getElementById('maintenance-alert');
                alertDiv.innerHTML = `
                    <div class="alert alert-${e.status}">
                        <strong>${e.message}</strong>
                        <br>
                        <small>${e.timestamp}</small>
                    </div>
                `;
            });
    </script>
</body>
</html>
```

### ملاحظات مهمة:

1. **اسم الحدث**: إذا استخدمت `broadcastAs()` في الـ Event، يجب أن تضع `.` قبل الاسم:
   ```javascript
   .listen('.maintenance.update', ...)  // مع broadcastAs()
   // أو
   .listen('SystemMaintenanceEvent', ...)  // بدون broadcastAs()
   ```

2. **القنوات الخاصة (Private Channels)**: تحتاج مصادقة:
   ```javascript
   Echo.private('system-maintenance')
       .listen('.maintenance.update', ...);
   ```

---

## الخطوة 7: إعداد القنوات الخاصة (Private Channels)

إذا أردت استخدام قناة خاصة، عدل `routes/channels.php`:

```php
use Illuminate\Support\Facades\Broadcast;

// قناة عامة
Broadcast::channel('system-maintenance', function ($user) {
    return true; // أي شخص يمكنه الاستماع
});

// قناة خاصة للمستخدمين فقط
Broadcast::channel('system-maintenance', function ($user) {
    return $user !== null; // فقط المستخدمين المسجلين
});

// قناة خاصة للمسؤولين فقط
Broadcast::channel('admin.system-maintenance', function ($user) {
    return $user && $user->is_admin === true;
});
```

---

## الخطوة 8: اختبار النظام

### 1. تأكد أن Reverb يعمل:
```bash
php artisan reverb:start
```

### 2. افتح المتصفح وافتح Console (F12)

### 3. في Terminal آخر، أرسل حدث:
```bash
php artisan tinker
```
ثم في Tinker:
```php
event(new App\Events\SystemMaintenanceEvent('Test message', 'success'));
```

### 4. يجب أن ترى الرسالة في Console المتصفح!

---

## أنواع القنوات (Channels)

### 1. Public Channel (قناة عامة):
```php
new Channel('channel-name')
```
```javascript
Echo.channel('channel-name')
```

### 2. Private Channel (قناة خاصة):
```php
new PrivateChannel('channel-name')
```
```javascript
Echo.private('channel-name')
```

### 3. Presence Channel (قناة الحضور):
```php
new PresenceChannel('channel-name')
```
```javascript
Echo.join('channel-name')
    .here((users) => {
        // المستخدمين المتصلين حالياً
    })
    .joining((user) => {
        // مستخدم جديد انضم
    })
    .leaving((user) => {
        // مستخدم غادر
    });
```

---

## استكشاف الأخطاء (Troubleshooting)

### المشكلة: لا يصل الحدث للمتصفح

1. **تأكد أن Reverb يعمل:**
   ```bash
   php artisan reverb:start
   ```

2. **تحقق من `.env`:**
   - `BROADCAST_CONNECTION=reverb`
   - جميع متغيرات `REVERB_*` موجودة

3. **تحقق من Console المتصفح:**
   - افتح F12 → Console
   - ابحث عن أخطاء WebSocket

4. **تحقق من CORS:**
   - تأكد أن `REVERB_HOST` صحيح

### المشكلة: خطأ في الاتصال

- تأكد أن `REVERB_PORT` في `.env` يطابق `VITE_REVERB_PORT`
- تأكد أن الخادم يعمل على نفس البورت

---

## مثال كامل

### Backend (Event):
```php
// app/Events/SystemMaintenanceEvent.php
class SystemMaintenanceEvent implements ShouldBroadcast
{
    public $message;
    
    public function __construct($message)
    {
        $this->message = $message;
    }
    
    public function broadcastOn(): array
    {
        return [new Channel('system-maintenance')];
    }
}
```

### إرسال الحدث:
```php
// في Controller
event(new SystemMaintenanceEvent('System is under maintenance'));
```

### Frontend:
```javascript
// في Blade template
Echo.channel('system-maintenance')
    .listen('SystemMaintenanceEvent', (e) => {
        alert(e.message);
    });
```

---

## نصائح مهمة

1. **احتفظ بخادم Reverb يعمل دائماً** في Production
2. **استخدم Supervisor** لإدارة Reverb في Production
3. **القنوات الخاصة أكثر أماناً** للبيانات الحساسة
4. **اختبر دائماً** قبل النشر

---

## أوامر مفيدة

```bash
# تشغيل Reverb
php artisan reverb:start

# إنشاء مفاتيح جديدة
php artisan reverb:generate-app-key

# تثبيت Reverb (إذا لم يكن مثبت)
php artisan reverb:install

# مسح الكاش
php artisan config:clear
php artisan cache:clear
```

---

## الخلاصة

1. ✅ أضف متغيرات `.env`
2. ✅ شغل `php artisan reverb:start`
3. ✅ أنشئ Event مع `ShouldBroadcast`
4. ✅ استخدم `broadcast()` لإرسال الحدث
5. ✅ استخدم `Echo.channel()` في JavaScript للاستماع

**الآن أنت جاهز لاستخدام Laravel Reverb! 🚀**

