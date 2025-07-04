<!-- plugins:js -->
<script src="{{asset('admin/assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    
    <script src="{{asset('admin/assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('admin/assets/js/chart.js')}}"></script>
    <script src="{{asset('admin/assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('admin/assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('admin/assets/js/misc.js')}}"></script>
    <script src="{{asset('admin/assets/js/settings.js')}}"></script>
    <script src="{{asset('admin/assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('admin/assets/js/dashboard.js')}}"></script>
    <!-- End custom js for this page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.getElementById('scroll-container');
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
                const walkX = (x - startX) * 2; // Scroll-fast
                const walkY = (y - startY) * 2; // Scroll-fast
                scrollContainer.scrollLeft = scrollLeft - walkX;
                scrollContainer.scrollTop = scrollTop - walkY;
            });

            
        $('#clearAll').click(function() {
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/clearAllproducts") }}',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(response) {
                        alert(response.success);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
    </script>
      <script>
  document.getElementById('importForm').addEventListener('submit', function (e) {
    const button = document.getElementById('importButton');
    const progressBar = document.getElementById('progressBar');
    const progressContainer = document.getElementById('importProgress');
    const fileInput = document.getElementById('file');

    if (!fileInput.value) {
      alert('Please choose a file.');
      e.preventDefault();
      return;
    }

    // Disable button to prevent resubmission
    button.disabled = true;
    button.textContent = 'Importing...';

    // Show progress UI
    progressContainer.style.display = 'block';

    // Simulate progress (for effect only — real backend progress needs sockets)
    let progress = 0;
    const interval = setInterval(() => {
      if (progress >= 100) {
        clearInterval(interval);
      } else {
        progress += 5; // You can adjust speed
        progressBar.style.width = progress + '%';
        progressBar.innerText = progress + '%';
      }
    }, 200); // Simulate loading
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
  let periodTotal = 0;

  // Select all rows that contain the grouped cart totals
  const totalRows = document.querySelectorAll('.total-row');

  // Loop through each total row and add its value to the period total
  totalRows.forEach(row => {
      const total = parseFloat(row.getAttribute('data-total')) || 0;
      periodTotal += total;
  });

  // Display the final period total in the designated element
  const periodTotalElement = document.getElementById('period-total');
  if (periodTotalElement) {
      periodTotalElement.textContent = periodTotal.toFixed(2);
  }
});


</script>

