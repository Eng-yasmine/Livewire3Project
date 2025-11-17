<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/admin/')->name('admin.')->group(function () {

    Route::middleware(['auth:admin'])->group(function () {

        Route::view('dashboard', 'admin.dashboard')->name('dashboard');

        Route::view('settings', 'admin.settings.index')->name('settings.index');

        Route::view('skills', 'admin.skills.index')->name('skills.index');

        // Routes للمحادثة المباشرة (Live Chat)
        Route::get('chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
        Route::post('chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
        Route::get('chat/messages', [\App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');

        // Routes لاختبار Reverb
        Route::view('reverb-test', 'admin.reverb-test')->name('reverb-test');
        
        // Route للاختبار المباشر (GET)
        Route::get('reverb-test/send-test', function () {
            $message = request('message', 'رسالة اختبار مباشرة');
            $status = request('status', 'info');
            
            try {
                $event = new \App\Events\SystemMaintenanceEvent($message, $status);
                
                // إرسال مباشر بدون Queue
                broadcast($event);
                
                \Log::info('Event sent via ShouldBroadcastNow', [
                    'message' => $message,
                    'status' => $status
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم إرسال الحدث بنجاح!',
                    'debug' => [
                        'broadcast_connection' => config('broadcasting.default'),
                        'queue_connection' => config('queue.default'),
                        'channel' => 'system-maintenance',
                        'event_name' => 'maintenance.update',
                        'should_broadcast_now' => true
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Error in send-test route', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ], 500);
            }
        })->name('reverb-test.send-test');
        
        Route::post('reverb-test/send', function () {
            $message = request('message', 'رسالة اختبار من السيرفر');
            $status = request('status', 'info');
            
            \Log::info('Sending broadcast event', [
                'message' => $message,
                'status' => $status,
                'broadcast_connection' => config('broadcasting.default')
            ]);
            
            try {
                $event = new \App\Events\SystemMaintenanceEvent($message, $status);
                broadcast($event);
                
                \Log::info('Event broadcasted successfully');
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم إرسال الحدث بنجاح!',
                    'debug' => [
                        'broadcast_connection' => config('broadcasting.default'),
                        'channel' => 'system-maintenance',
                        'event_name' => 'maintenance.update'
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Error broadcasting event', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ], 500);
            }
        })->name('reverb-test.send');
    });
    Route::middleware(['guest:admin'])->group(function () {

        Route::view('login', 'admin.auth.login')->name('login');
    });
});
