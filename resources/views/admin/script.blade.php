<!-- plugins:js -->
<script src="{{ asset('admin/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('admin/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('admin/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('admin/assets/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('admin/assets/js/chart.js') }}"></script>
<script src="{{ asset('admin/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('admin/assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('admin/assets/js/misc.js') }}"></script>
<script src="{{ asset('admin/assets/js/settings.js') }}"></script>
<script src="{{ asset('admin/assets/js/todolist.js') }}"></script>
<!-- endinject -->

<!-- Custom js for this page -->
<script src="{{ asset('admin/assets/js/dashboard.js') }}"></script>
<!-- End custom js for this page -->

<!-- Scrollable container drag-scroll -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const scrollContainer = document.getElementById('scroll-container');
    if (scrollContainer) {
        let isDown = false;
        let startX, startY, scrollLeft, scrollTop;

        scrollContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            startX = e.pageX - scrollContainer.offsetLeft;
            startY = e.pageY - scrollContainer.offsetTop;
            scrollLeft = scrollContainer.scrollLeft;
            scrollTop = scrollContainer.scrollTop;
            scrollContainer.style.cursor = 'grabbing';
        });

        scrollContainer.addEventListener('mouseleave', () => {
            isDown = false;
            scrollContainer.style.cursor = 'grab';
        });

        scrollContainer.addEventListener('mouseup', () => {
            isDown = false;
            scrollContainer.style.cursor = 'grab';
        });

        scrollContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scrollContainer.offsetLeft;
            const y = e.pageY - scrollContainer.offsetTop;
            const walkX = (x - startX) * 2;
            const walkY = (y - startY) * 2;
            scrollContainer.scrollLeft = scrollLeft - walkX;
            scrollContainer.scrollTop = scrollTop - walkY;
        });
    }

    // Clear all products
    $('#clearAll').click(function () {
        $.ajax({
            type: 'POST',
            url: '{{ url("/clearAllproducts") }}',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
                alert(response.success);
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
    });
});
</script>

<!-- Import progress simulation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('importForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const button = document.getElementById('importButton');
            const progressBar = document.getElementById('progressBar');
            const progressContainer = document.getElementById('importProgress');
            const fileInput = document.getElementById('file');

            if (!fileInput.value) {
                alert('Please choose a file.');
                e.preventDefault();
                return;
            }

            button.disabled = true;
            button.textContent = 'Importing...';
            progressContainer.style.display = 'block';

            let progress = 0;
            const interval = setInterval(() => {
                if (progress >= 100) {
                    clearInterval(interval);
                } else {
                    progress += 5;
                    progressBar.style.width = progress + '%';
                    progressBar.innerText = progress + '%';
                }
            }, 200);
        });
    }
});
</script>

<!-- Period total calculation -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    let periodTotal = 0;
    const totalRows = document.querySelectorAll('.total-row');

    totalRows.forEach(row => {
        const total = parseFloat(row.getAttribute('data-total')) || 0;
        periodTotal += total;
    });

    const periodTotalElement = document.getElementById('period-total');
    if (periodTotalElement) {
        periodTotalElement.textContent = periodTotal.toFixed(2);
    }
});
</script>

 <script>
      // Show/hide toggle
      document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
          const target = document.querySelector(this.dataset.target);
          const icon = this.querySelector('i');
          if (target.type === 'password') {
            target.type = 'text';
            icon.classList.replace('mdi-eye', 'mdi-eye-off');
          } else {
            target.type = 'password';
            icon.classList.replace('mdi-eye-off', 'mdi-eye');
          }
        });
      });

      // Password rules
      const passwordInput = document.getElementById('password');
      const rules = {
        length: document.getElementById('rule-length'),
        uppercase: document.getElementById('rule-uppercase'),
        lowercase: document.getElementById('rule-lowercase'),
        number: document.getElementById('rule-number'),
        symbol: document.getElementById('rule-symbol'),
      };

      passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;
        updateRule(rules.length, value.length >= 8);
        updateRule(rules.uppercase, /[A-Z]/.test(value));
        updateRule(rules.lowercase, /[a-z]/.test(value));
        updateRule(rules.number, /\d/.test(value));
        updateRule(rules.symbol, /[^A-Za-z0-9]/.test(value));
      });

      function updateRule(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
          icon.classList.replace('mdi-circle', 'mdi-check-circle');
          icon.classList.replace('text-secondary', 'text-success');
        } else {
          icon.classList.replace('mdi-check-circle', 'mdi-circle');
          icon.classList.replace('text-success', 'text-secondary');
        }
      }
    </script>