<nav class="navbar navbar-expand-lg navbar-dark bg-secondary border-bottom">
    <div class="container-fluid">
        <button class="btn btn-outline-light me-3" id="menu-toggle"><i class="fas fa-bars"></i></button>
        <span class="navbar-brand mb-0 h1">Service Admin</span>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link text-white" href="#"><i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}</a>
            </li>
        </ul>
    </div>
</nav>
