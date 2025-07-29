<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.css')
    <style>
      .strikethrough {
        text-decoration: line-through;
        color: red;
      }
      body {
        margin: 0px;
        border: 0px;
      }
      .scroll-container {
        width: auto;
        height: 100vw;
        overflow: auto;
        cursor: grab;
        user-select: none;
      }
      .scroll-container:active {
        cursor: grabbing;
      }
    </style>
  </head>
  <body>
    @include('admin.sidebar')
    @include('admin.header')
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="container-md mt-2">
 @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
              @endif
              @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
              @endif
              <div class="container py-5">
  
  <!-- Title -->
  <h1 class="text-success text-capitalize">System Documentation</h1>

  <!-- Overview Section -->
  <section class="mb-5">
    <h2 class="page-title text-info">Overview</h2>
    <p class="section-text text-black">
      <strong>Justart Tech SaaS</strong> is a Software as a Service (SaaS) platform designed to digitize business operations for small and medium-sized enterprises. 
      It enables business owners to manage products, sales, employees, customers, and finances — all from a centralized dashboard that works on mobile and desktop. 
      The platform operates on a fair subscription model based on a small percentage of the business's <strong>net profit</strong>, making it affordable and performance-based.
    </p>
    <p class="text-black">
      When a business owner logs in, they land on this dashboard. It provides a quick glance at the business performance: today’s sales, total products, and total customers.
      The interface is clean and responsive, adapting seamlessly to desktops and mobile devices.
    </p>
  </section>
             <section class="mb-2">
  <div class="preview-wrapper">
    <!-- Fullscreen Desktop Image -->
    <img src="./img/homeadmin.png" alt="Full View" class="preview-desktop" />

    <!-- Mobile Mockup Overlap -->
    <div class="preview-mobile">
      <img src="./img/phoneadmin.png" alt="Mobile View" />
    </div>
  </div>
 
    </section>
    <div class="mt-5">
       
    </div>
                 <section class="mt-4">
                   <h2 class="text-2xl text-success font-semibold mb-3">Sidebar Navigation Overview</h2>
  <div class="preview-wrapper">
    <!-- Fullscreen Desktop Image -->
    <img src="./img/desktop_sidebar.png" alt="Full View" class="preview-desktop" />

    <!-- Mobile Mockup Overlap -->
    <div class="preview-mobile">
      <img src="./img/phone_sidebar.png" alt="Mobile View" />
    </div>
  </div>
   <ul class="list-disc pl-6 text-lg text-black mt-4">
      <li><strong>Dashboard:</strong> Displays admin name, email, profile link, and logout.</li>
      <li><strong>Home:</strong> The main dashboard showing sales and product stats.</li>
      <li><strong>Products:</strong> Add/view products, track imports, and monitor stock levels (low/out-of-stock alerts).</li>
      <li><strong>Category:</strong> Manage product categories for better organization.</li>
      <li><strong>Settings:</strong> Configure M-Pesa integration (short code, consumer key/secret, passkey) with help from the platform provider.</li>
      <li><strong>Users:</strong> Add employees/users to your business and manage their roles.</li>
      <li><strong>Customers:</strong> Add and manage customer details.</li>
      <li><strong>Sales:</strong> View all sales, apply filters by date, and get overall totals.</li>
      <li><strong>Income Statement:</strong> Generate a statement showing gross profit, other incomes, expenses, and net profit.</li>
      <li><strong>Documentation:</strong> You're here!</li>
    </ul>
    </section>
     <!-- User Panel Section -->
  <section class="mb-6">
    <h2 class="text-2xl font-semibold mb-3 text-success">User Panel Overview</h2>
  <div class="mt-5">
     <p class="text-lg text-black">
      Business users (e.g., employee) can log in using their credentials to view products, make sales, and handle customers orders.
      The Interface is simplified, focused on the tasks they are authorized to perform. Each action is logged and recorded under the business’s data.
    </p>
    </section>
<section class="mt-5">
  <div class="preview-wrapper">
    <!-- Fullscreen Desktop Image -->
    <img src="./img/desktop_user.png" alt="Full View" class="preview-desktop" />

    <!-- Mobile Mockup Overlap -->
    <div class="preview-mobile">
      <img src="./img/user_phone.png" alt="Mobile View" />
    </div>
  </div>
 
    </section>
   <div class="mt-5"> <h2 class="text-2xl font-semibold mb-3 text-success">How to Make a Sale</h2>
   <p class="section-text text-black">
      When a business employee or user logs in, this is the screen they use to make a sale. They can:
    </p>
  </div>
 
  <section class="margin-top-custom">
  <div class="preview-wrapper">
    <!-- Fullscreen Desktop Image -->
    <img src="./img/desktop_cart.png" alt="Full View" class="preview-desktop" />

    <!-- Mobile Mockup Overlap -->
    <div class="preview-mobile">
      <img src="./img/phone_cart.png" alt="Mobile View" />
    </div>
  </div>
 
    </section>
     <ul class="section-list text-black mt-4">
      <li>Select a product or scan to add to the cart.</li>
      <li>Update the quantity or set a temporary active price for a product in the cart.</li>
      <li>Remove an item using the <strong>Delete</strong> button.</li>
      <li>Use the <strong>Checkout</strong> dropdown to complete the sale with either Cash, M-Pesa, or Card.</li>
      <li>Click <strong>Hold Sale</strong> to temporarily save the cart without completing the checkout.</li>
      <li>Use the <strong>Clear Cart</strong> button to remove all items from the cart.</li>
      <li>Input <strong>Cash Given</strong> to calculate change using the <strong>Balance</strong> button.</li>
      <li>Enter a customer name and use <strong>Add to Debt</strong> to save a debt transaction instead of payment.</li>
    </ul>
    <p class="section-text text-success">
      This screen simplifies the sales process for staff, making it efficient to serve customers quickly while maintaining accurate records.
    </p>
 </div>
   
   
  </div>
   
    </div>
    </div>
    </div>
 
    @include('admin.script')

    
  </body>
</html>
