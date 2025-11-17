# مثال عملي: استخدام SystemMaintenanceEvent مع Reverb

## الخطوات السريعة

### 1. إعداد ملف `.env`

أضف هذه السطور في ملف `.env`:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**أو ببساطة شغل:**
```bash
php artisan reverb:install
```

---

### 2. تشغيل خادم Reverb

افتح Terminal جديد واكتب:


```

**احتفظ بهذا Terminal مفتوحاً!**

---

### 3. إرسال الحدث من Controller

مثال في Controller:

```php
<?php

namespace App\Http\Controllers;

use App\Events\SystemMaintenanceEvent;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function startMaintenance()
    {
        // إرسال حدث الصيانة
        broadcast(new SystemMaintenanceEvent(
            'System will be under maintenance in 10 minutes',
            'warning'
        ));
        
        return response()->json(['message' => 'Maintenance notification sent']);
    }
    
    public function endMaintenance()
    {
        broadcast(new SystemMaintenanceEvent(
            'Maintenance completed. System is back online!',
            'success'
        ));
        
        return response()->json(['message' => 'Maintenance completed notification sent']);
    }
}
```

---

### 4. إضافة Route

في `routes/web.php`:

```php
use App\Http\Controllers\MaintenanceController;

Route::post('/maintenance/start', [MaintenanceController::class, 'startMaintenance']);
Route::post('/maintenance/end', [MaintenanceController::class, 'endMaintenance']);
```

---

### 5. الاستماع في Frontend

في Blade Template (مثلاً `resources/views/admin/dashboard.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="maintenance-alert" style="display: none;"></div>
    
    <h1>Admin Dashboard</h1>
    
    <script>
        // الاستماع لحدث الصيانة
        Echo.channel('system-maintenance')
            .listen('.maintenance.update', (e) => {
                console.log('Maintenance Event Received:', e);
                
                // عرض التنبيه
                const alertDiv = document.getElementById('maintenance-alert');
                alertDiv.style.display = 'block';
                
                // تحديد لون التنبيه حسب الحالة
                let alertClass = 'alert-info';
                if (e.status === 'warning') alertClass = 'alert-warning';
                if (e.status === 'error') alertClass = 'alert-danger';
                if (e.status === 'success') alertClass = 'alert-success';
                
                alertDiv.innerHTML = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <strong>${e.message}</strong>
                        <br>
                        <small>${e.timestamp}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                // إخفاء التنبيه بعد 10 ثواني
                setTimeout(() => {
                    alertDiv.style.display = 'none';
                }, 10000);
            });
    </script>
</body>
</html>
```

---

### 6. اختبار النظام

#### الطريقة 1: من Tinker

```bash
php artisan tinker
```

ثم:

```php
use App\Events\SystemMaintenanceEvent;

// إرسال حدث
broadcast(new SystemMaintenanceEvent('Test message', 'info'));
```

#### الطريقة 2: من Route مباشرة

في `routes/web.php`:

```php
Route::get('/test-maintenance', function () {
    broadcast(new \App\Events\SystemMaintenanceEvent(
        'This is a test maintenance message',
        'warning'
    ));
    
    return 'Maintenance event sent! Check your browser console.';
});
```

ثم افتح المتصفح على: `http://localhost:8000/test-maintenance`

---

## مثال كامل: صفحة اختبار

أنشئ ملف `resources/views/test-reverb.blade.php`:

```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار Reverb</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid;
        }
        .alert-info { background: #d1ecf1; border-color: #bee5eb; }
        .alert-warning { background: #fff3cd; border-color: #ffeaa7; }
        .alert-danger { background: #f8d7da; border-color: #f5c6cb; }
        .alert-success { background: #d4edda; border-color: #c3e6cb; }
        .status {
            padding: 10px;
            margin: 10px 0;
            background: #f0f0f0;
            border-radius: 5px;
        }
        .connected { background: #d4edda; }
        .disconnected { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>اختبار Laravel Reverb</h1>
    
    <div id="connection-status" class="status disconnected">
        ❌ غير متصل
    </div>
    
    <div id="maintenance-alert"></div>
    
    <h2>الأحداث المستلمة:</h2>
    <div id="events-log" style="background: #f9f9f9; padding: 15px; border-radius: 5px; max-height: 400px; overflow-y: auto;">
        <p>لا توجد أحداث حتى الآن...</p>
    </div>
    
    <script>
        // التحقق من حالة الاتصال
        const statusDiv = document.getElementById('connection-status');
        const eventsLog = document.getElementById('events-log');
        
        // تحديث حالة الاتصال
        function updateConnectionStatus(connected) {
            if (connected) {
                statusDiv.className = 'status connected';
                statusDiv.textContent = '✅ متصل بـ Reverb';
            } else {
                statusDiv.className = 'status disconnected';
                statusDiv.textContent = '❌ غير متصل';
            }
        }
        
        // إضافة حدث للسجل
        function addEventToLog(eventData) {
            const eventDiv = document.createElement('div');
            eventDiv.style.cssText = 'padding: 10px; margin: 5px 0; background: white; border-left: 4px solid #007bff; border-radius: 3px;';
            eventDiv.innerHTML = `
                <strong>${eventData.message}</strong><br>
                <small>الحالة: ${eventData.status} | الوقت: ${eventData.timestamp}</small>
            `;
            eventsLog.insertBefore(eventDiv, eventsLog.firstChild);
        }
        
        // التحقق من وجود Echo
        if (typeof Echo !== 'undefined') {
            updateConnectionStatus(true);
            
            // الاستماع لحدث الصيانة
            Echo.channel('system-maintenance')
                .listen('.maintenance.update', (e) => {
                    console.log('Maintenance Event:', e);
                    
                    // عرض التنبيه
                    const alertDiv = document.getElementById('maintenance-alert');
                    let alertClass = 'alert-info';
                    if (e.status === 'warning') alertClass = 'alert-warning';
                    if (e.status === 'error') alertClass = 'alert-danger';
                    if (e.status === 'success') alertClass = 'alert-success';
                    
                    alertDiv.innerHTML = `
                        <div class="alert ${alertClass}">
                            <strong>${e.message}</strong><br>
                            <small>${e.timestamp}</small>
                        </div>
                    `;
                    
                    // إضافة للسجل
                    addEventToLog(e);
                    
                    // إخفاء التنبيه بعد 5 ثواني
                    setTimeout(() => {
                        alertDiv.innerHTML = '';
                    }, 5000);
                });
        } else {
            updateConnectionStatus(false);
            console.error('Echo is not defined! Make sure you have included the app.js file.');
        }
    </script>
</body>
</html>
```

أضف Route:

```php
Route::get('/test-reverb', function () {
    return view('test-reverb');
});
```

---

## ملاحظات مهمة

1. **تأكد أن Reverb يعمل** قبل الاختبار
2. **افتح Console المتصفح (F12)** لرؤية الأخطاء
3. **استخدم `broadcast()` وليس `event()`** لإرسال عبر WebSocket
4. **اسم الحدث في JavaScript**: إذا استخدمت `broadcastAs()`، أضف `.` قبل الاسم

---

## استكشاف الأخطاء

### المشكلة: "Echo is not defined"

**الحل:**
- تأكد أن `@vite(['resources/js/app.js'])` موجود في Blade
- شغل `npm run dev` أو `npm run build`
- تأكد أن `resources/js/echo.js` موجود ومضاف في `bootstrap.js`

### المشكلة: لا يصل الحدث

**الحل:**
1. تأكد أن Reverb يعمل: `php artisan reverb:start`
2. تحقق من `.env` - جميع المتغيرات موجودة
3. افتح Console المتصفح وراجع الأخطاء
4. تأكد أن `BROADCAST_CONNECTION=reverb` في `.env`

---

## جاهز! 🎉

الآن يمكنك استخدام Reverb لإرسال رسائل فورية من السيرفر للمتصفحات!

