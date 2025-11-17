  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{asset('admin-assets/vendor/libs/jquery/jquery.js')}}"></script>
  <script src="{{asset('admin-assets/vendor/libs/popper/popper.js')}}"></script>
  <script src="{{asset('admin-assets/vendor/js/bootstrap.js')}}"></script>
  <script src="{{asset('admin-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

  <script src="{{asset('admin-assets/vendor/js/menu.js')}}"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{asset('admin-assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

  <!-- Main JS -->
  <script src="{{asset('admin-assets/js/main.js')}}"></script>

  <!-- Page JS -->
  <script src="{{asset('admin-assets/js/dashboards-analytics.js')}}"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  @livewireScripts
 <script>
  // دالة لإخفاء رسالة النجاح
  function hideSuccessMessage() {
      const successMessage = document.getElementById('success-message');
      if (successMessage) {
          // استخدام fade out animation
          successMessage.style.transition = 'opacity 0.5s';
          successMessage.style.opacity = '0';
          setTimeout(() => {
              successMessage.remove();
          }, 500); // الانتظار لانتهاء animation
      }
  }

  // إخفاء الرسالة إذا كانت موجودة عند تحميل الصفحة
  document.addEventListener('DOMContentLoaded', () => {
      const successMessage = document.getElementById('success-message');
      if (successMessage) {
          setTimeout(hideSuccessMessage, 2000); // إخفاء بعد ثانيتين
      }
  });

  // إخفاء الرسالة عند استقبال event من Livewire
  document.addEventListener('livewire:init', () => {
      Livewire.on('settings-updated', () => {
          // إخفاء رسالة النجاح بعد ثانيتين
          setTimeout(hideSuccessMessage, 2000);
      });
  });
</script>

<!-- Vite for Echo (Reverb) -->
@vite(['resources/css/app.css', 'resources/js/app.js'])