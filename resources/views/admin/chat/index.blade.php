@extends('admin.master')

@section('title', 'المحادثة المباشرة')

@section('admin-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">المحادثة /</span> لايف تشات
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-message-rounded"></i> المحادثة المباشرة
                    </h5>
                </div>
                <div class="card-body p-0">
                    <!-- منطقة الرسائل -->
                    <div id="chat-messages" class="chat-messages p-4" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
                        @foreach($messages as $message)
                            <div class="message mb-3 {{ $message->user_id == auth('admin')->id() ? 'message-own' : '' }}">
                                <div class="d-flex align-items-start">
                                    <div class="message-content {{ $message->user_id == auth('admin')->id() ? 'ms-auto' : '' }}" style="max-width: 70%;">
                                        <div class="card {{ $message->user_id == auth('admin')->id() ? 'bg-primary text-white' : 'bg-white' }}">
                                            <div class="card-body p-2">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <strong class="small">{{ $message->username ?? 'مجهول' }}</strong>
                                                    <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                                </div>
                                                <p class="mb-0">{{ $message->message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- نموذج إرسال الرسالة -->
                    <div class="card-footer bg-white border-top">
                        <form id="chat-form" class="d-flex gap-2">
                            @csrf
                            <input 
                                type="text" 
                                id="message-input" 
                                class="form-control" 
                                placeholder="اكتب رسالتك هنا..." 
                                autocomplete="off"
                                required
                            >
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-send"></i> إرسال
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-messages {
        scroll-behavior: smooth;
    }
    
    .message-own .message-content {
        margin-left: auto;
    }
    
    .message-own .card {
        background: #007bff !important;
        color: white !important;
    }
    
    .message-own .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }
</style>

<script>
    // التمرير لأسفل تلقائياً
    function scrollToBottom() {
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // إضافة رسالة جديدة للواجهة
    function addMessageToChat(messageData) {
        const chatMessages = document.getElementById('chat-messages');
        const isOwnMessage = messageData.user_id == {{ auth('admin')->id() }};
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message mb-3 ${isOwnMessage ? 'message-own' : ''}`;
        
        messageDiv.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="message-content ${isOwnMessage ? 'ms-auto' : ''}" style="max-width: 70%;">
                    <div class="card ${isOwnMessage ? 'bg-primary text-white' : 'bg-white'}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <strong class="small">${messageData.username || 'مجهول'}</strong>
                                <small class="text-muted">${messageData.time || messageData.created_at}</small>
                            </div>
                            <p class="mb-0">${messageData.message}</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    // إرسال الرسالة
    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        // تعطيل النموذج مؤقتاً
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> جاري الإرسال...';
        
        try {
            const response = await fetch('{{ route("admin.chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    message: message
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                messageInput.value = '';
                // الرسالة ستصل تلقائياً عبر Echo
                console.log('Message sent successfully');
            } else {
                alert('فشل إرسال الرسالة: ' + (data.message || 'حدث خطأ'));
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('حدث خطأ أثناء إرسال الرسالة');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bx-send"></i> إرسال';
            messageInput.focus();
        }
    });

    // الاستماع للأحداث عبر Echo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔍 Initializing chat...');
        
        // انتظار تحميل Echo
        let attempts = 0;
        const maxAttempts = 10;
        
        const initChat = setInterval(function() {
            attempts++;
            
            if (typeof Echo !== 'undefined') {
                clearInterval(initChat);
                console.log('✅ Echo loaded, subscribing to chat channel...');
                
                try {
                    // الاشتراك في قناة المحادثة
                    const channel = Echo.channel('chat');
                    
                    // الاستماع لحدث إرسال الرسالة
                    channel.listen('.message.sent', (data) => {
                        console.log('📨 New message received:', data);
                        addMessageToChat(data);
                    });
                    
                    // معالجة الأخطاء
                    channel.error((error) => {
                        console.error('❌ Channel error:', error);
                    });
                    
                    channel.subscribed(() => {
                        console.log('✅ Successfully subscribed to chat channel');
                    });
                    
                    console.log('✅ Chat initialized successfully');
                    
                } catch (error) {
                    console.error('❌ Error initializing chat:', error);
                }
                
            } else if (attempts >= maxAttempts) {
                clearInterval(initChat);
                console.error('❌ Echo not found after', maxAttempts, 'attempts');
            }
        }, 500);
        
        // التمرير لأسفل عند تحميل الصفحة
        scrollToBottom();
    });

    // التمرير لأسفل عند الضغط على Enter
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chat-form').dispatchEvent(new Event('submit'));
        }
    });
</script>

@endsection

