@extends('admin.master')

@section('title', 'اختبار Reverb')

@section('admin-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">اختبار /</span> Laravel Reverb
    </h4>

    <!-- Connection Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">حالة الاتصال</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="testConnection()">
                            <i class="bx bx-refresh"></i> اختبار الاتصال
                        </button>
                    </div>
                    <div id="connection-status" class="alert alert-danger mb-0">
                        <i class="bx bx-error-circle"></i> غير متصل بـ Reverb
                    </div>
                    <div id="connection-details" class="mt-2 small text-muted"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Event Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">إرسال حدث تجريبي</h5>
                </div>
                <div class="card-body">
                    <form id="send-event-form">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">الرسالة</label>
                            <input type="text" class="form-control" id="message" name="message" 
                                   value="هذه رسالة اختبار من Reverb" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">نوع الرسالة</label>
                            <select class="form-select" id="status" name="status">
                                <option value="info">معلومات (Info)</option>
                                <option value="success">نجاح (Success)</option>
                                <option value="warning">تحذير (Warning)</option>
                                <option value="error">خطأ (Error)</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-send"></i> إرسال الحدث
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="sendTestEvent()">
                                <i class="bx bx-test-tube"></i> اختبار مباشر
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Alert -->
    <div class="row mb-4">
        <div class="col-12">
            <div id="live-alert"></div>
        </div>
    </div>

    <!-- Events Log -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">سجل الأحداث المستلمة</h5>
                    <button class="btn btn-sm btn-outline-secondary" onclick="clearEventsLog()">
                        <i class="bx bx-trash"></i> مسح السجل
                    </button>
                </div>
                <div class="card-body">
                    <div id="events-log" style="max-height: 400px; overflow-y: auto; min-height: 200px;">
                        <p class="text-muted text-center">لا توجد أحداث حتى الآن...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // تحديث حالة الاتصال
    function updateConnectionStatus(connected) {
        const statusDiv = document.getElementById('connection-status');
        if (connected) {
            statusDiv.className = 'alert alert-success mb-0';
            statusDiv.innerHTML = '<i class="bx bx-check-circle"></i> متصل بـ Reverb بنجاح';
        } else {
            statusDiv.className = 'alert alert-danger mb-0';
            statusDiv.innerHTML = '<i class="bx bx-error-circle"></i> غير متصل بـ Reverb - تأكد أن الخادم يعمل';
        }
    }

    // إضافة حدث للسجل
    function addEventToLog(eventData) {
        const eventsLog = document.getElementById('events-log');
        
        // إزالة الرسالة الافتراضية
        if (eventsLog.querySelector('p.text-muted')) {
            eventsLog.innerHTML = '';
        }
        
        const eventDiv = document.createElement('div');
        eventDiv.className = 'card mb-2';
        
        // تحديد لون حسب الحالة
        let borderColor = '#0d6efd'; // info - blue
        let statusText = 'معلومات';
        let statusIcon = 'bx-info-circle';
        
        if (eventData.status === 'success') {
            borderColor = '#198754';
            statusText = 'نجاح';
            statusIcon = 'bx-check-circle';
        } else if (eventData.status === 'warning') {
            borderColor = '#ffc107';
            statusText = 'تحذير';
            statusIcon = 'bx-error';
        } else if (eventData.status === 'error') {
            borderColor = '#dc3545';
            statusText = 'خطأ';
            statusIcon = 'bx-x-circle';
        }
        
        eventDiv.style.borderLeft = `4px solid ${borderColor}`;
        eventDiv.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">
                            <i class="bx ${statusIcon}"></i> ${statusText}
                        </h6>
                        <p class="mb-1">${eventData.message}</p>
                        <small class="text-muted">
                            <i class="bx bx-time"></i> ${eventData.timestamp}
                        </small>
                    </div>
                </div>
            </div>
        `;
        
        eventsLog.insertBefore(eventDiv, eventsLog.firstChild);
    }

    // عرض تنبيه مباشر
    function showLiveAlert(eventData) {
        const alertDiv = document.getElementById('live-alert');
        
        let alertClass = 'alert-info';
        let alertIcon = 'bx-info-circle';
        
        if (eventData.status === 'success') {
            alertClass = 'alert-success';
            alertIcon = 'bx-check-circle';
        } else if (eventData.status === 'warning') {
            alertClass = 'alert-warning';
            alertIcon = 'bx-error';
        } else if (eventData.status === 'error') {
            alertClass = 'alert-danger';
            alertIcon = 'bx-x-circle';
        }
        
        alertDiv.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bx ${alertIcon}"></i>
                <strong>${eventData.message}</strong>
                <br>
                <small>${eventData.timestamp}</small>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // إخفاء التنبيه بعد 5 ثواني
        setTimeout(() => {
            const alert = alertDiv.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alertDiv.innerHTML = '';
                }, 300);
            }
        }, 5000);
    }

    // مسح سجل الأحداث
    function clearEventsLog() {
        const eventsLog = document.getElementById('events-log');
        eventsLog.innerHTML = '<p class="text-muted text-center">لا توجد أحداث حتى الآن...</p>';
    }

    // إرسال حدث تجريبي مباشر
    async function sendTestEvent() {
        try {
            console.log('🧪 Sending test event directly...');
            
            const response = await fetch('{{ route("admin.reverb-test.send-test") }}?message=اختبار مباشر من المتصفح&status=success', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            });
            
            const data = await response.json();
            console.log('Test event response:', data);
            
            if (data.success) {
                alert('تم إرسال الحدث! تحقق من Console وسجل الأحداث.');
                console.log('Debug info:', data.debug);
            } else {
                alert('فشل إرسال الحدث: ' + data.message);
            }
        } catch (error) {
            console.error('Error sending test event:', error);
            alert('حدث خطأ أثناء إرسال الحدث');
        }
    }

    // اختبار الاتصال
    function testConnection() {
        const detailsDiv = document.getElementById('connection-details');
        detailsDiv.innerHTML = '<i class="bx bx-loader bx-spin"></i> جاري الاختبار...';
        
        console.log('🧪 Testing connection...');
        console.log('Echo defined:', typeof Echo !== 'undefined');
        
        if (typeof Echo === 'undefined') {
            detailsDiv.innerHTML = '<span class="text-danger">❌ Echo غير محمل</span>';
            return;
        }
        
        console.log('Echo object:', Echo);
        console.log('Echo connector:', Echo.connector);
        
        if (Echo.connector && Echo.connector.pusher) {
            const state = Echo.connector.pusher.connection.state;
            const socketId = Echo.connector.pusher.connection.socket_id;
            
            detailsDiv.innerHTML = `
                <div class="mt-2">
                    <strong>الحالة:</strong> ${state}<br>
                    <strong>Socket ID:</strong> ${socketId || 'غير متاح'}<br>
                    <strong>Channel:</strong> system-maintenance
                </div>
            `;
            
            console.log('Connection state:', state);
            console.log('Socket ID:', socketId);
        } else {
            detailsDiv.innerHTML = '<span class="text-danger">❌ Connector غير موجود</span>';
        }
    }

    // إرسال حدث من النموذج
    document.getElementById('send-event-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const message = formData.get('message');
        const status = formData.get('status');
        
        try {
            const response = await fetch('{{ route("admin.reverb-test.send") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    status: status
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // إظهار رسالة نجاح
                const alertDiv = document.getElementById('live-alert');
                alertDiv.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle"></i>
                        <strong>تم إرسال الحدث بنجاح!</strong> تحقق من سجل الأحداث أدناه.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                
                setTimeout(() => {
                    const alert = alertDiv.querySelector('.alert');
                    if (alert) {
                        alert.classList.remove('show');
                        setTimeout(() => {
                            alertDiv.innerHTML = '';
                        }, 300);
                    }
                }, 3000);
            }
        } catch (error) {
            console.error('Error sending event:', error);
            alert('حدث خطأ أثناء إرسال الحدث');
        }
    });

    // التحقق من وجود Echo والاستماع للأحداث
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 Starting Echo initialization...');
        
        // دالة للتحقق من Echo
        function initEcho() {
            if (typeof Echo !== 'undefined') {
                console.log('✅ Echo is defined');
                console.log('Echo object:', Echo);
                
                try {
                    // التحقق من وجود connector
                    if (!Echo.connector || !Echo.connector.pusher) {
                        console.error('❌ Echo connector not found');
                        updateConnectionStatus(false);
                        return;
                    }
                    
                    console.log('✅ Echo connector found');
                    
                    // معالجة الأخطاء
                    Echo.connector.pusher.connection.bind('error', function(err) {
                        console.error('❌ Connection error:', err);
                        updateConnectionStatus(false);
                    });
                    
                    Echo.connector.pusher.connection.bind('connected', function() {
                        console.log('✅ Connected to Reverb successfully');
                        updateConnectionStatus(true);
                    });
                    
                    Echo.connector.pusher.connection.bind('disconnected', function() {
                        console.log('❌ Disconnected from Reverb');
                        updateConnectionStatus(false);
                    });
                    
                    Echo.connector.pusher.connection.bind('state_change', function(states) {
                        console.log('🔄 Connection state changed:', states);
                    });
                    
                    // التحقق من حالة الاتصال الحالية
                    const state = Echo.connector.pusher.connection.state;
                    console.log('📊 Current connection state:', state);
                    
                    if (state === 'connected') {
                        updateConnectionStatus(true);
                    } else {
                        updateConnectionStatus(false);
                    }
                    
                    // الاستماع لحدث الصيانة
                    console.log('📡 Subscribing to channel: system-maintenance');
                    
                    const channel = Echo.channel('system-maintenance');
                    
                    // الاستماع للحدث مع الاسم المخصص
                    channel.listen('.maintenance.update', (e) => {
                        console.log('📨 Event received (with broadcastAs):', e);
                        console.log('Event data:', JSON.stringify(e, null, 2));
                        
                        // عرض التنبيه المباشر
                        showLiveAlert(e);
                        
                        // إضافة للسجل
                        addEventToLog(e);
                    });
                    
                    // أيضاً الاستماع للاسم الافتراضي (SystemMaintenanceEvent)
                    channel.listen('SystemMaintenanceEvent', (e) => {
                        console.log('📨 Event received (default name):', e);
                        console.log('Event data:', JSON.stringify(e, null, 2));
                        
                        // عرض التنبيه المباشر
                        showLiveAlert(e);
                        
                        // إضافة للسجل
                        addEventToLog(e);
                    });
                    
                    // الاستماع لجميع الأحداث على القناة (للتشخيص)
                    channel.listenToAll((eventName, data) => {
                        console.log('📨 All events listener - Event:', eventName, 'Data:', data);
                    });
                    
                    // معالجة الأخطاء في القناة
                    channel.error((error) => {
                        console.error('❌ Channel error:', error);
                    });
                    
                    // معالجة نجاح الاشتراك
                    channel.subscribed(() => {
                        console.log('✅ Successfully subscribed to channel');
                    });
                    
                    console.log('✅ Channel subscription initiated');
                    
                } catch (error) {
                    console.error('❌ Error initializing Echo:', error);
                    updateConnectionStatus(false);
                }
                
            } else {
                updateConnectionStatus(false);
                console.error('❌ Echo is not defined!');
                console.log('تأكد من:');
                console.log('1. أن Vite موجود في الصفحة');
                console.log('2. أن npm run dev يعمل');
                console.log('3. أن ملف resources/js/echo.js موجود');
                console.log('4. أن متغيرات VITE_REVERB_* موجودة في .env');
            }
        }
        
        // محاولة متعددة للتحقق من Echo
        let attempts = 0;
        const maxAttempts = 10;
        
        const checkEcho = setInterval(function() {
            attempts++;
            console.log(`🔍 Attempt ${attempts} to find Echo...`);
            
            if (typeof Echo !== 'undefined') {
                clearInterval(checkEcho);
                initEcho();
            } else if (attempts >= maxAttempts) {
                clearInterval(checkEcho);
                console.error('❌ Echo not found after', maxAttempts, 'attempts');
                updateConnectionStatus(false);
            }
        }, 500); // التحقق كل 500ms
    });
</script>

@endsection

