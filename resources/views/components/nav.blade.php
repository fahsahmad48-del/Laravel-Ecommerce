
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">

  <div class="container">

    <!-- BRAND -->
    <a class="navbar-brand fw-bold" href="/">MiniStore</a>

    <!-- TOGGLE -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- NAV LINKS -->
    <div class="collapse navbar-collapse" id="navbarNav">

      <!-- LEFT -->
      <ul class="navbar-nav me-auto">

        <li class="nav-item">
          <a class="nav-link" href="/">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('products') }}">Products</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('cart') }}">Cart</a>
        </li>

        <!-- ADMIN LINKS -->
        @if(auth()->check() && auth()->user()->is_admin)

          <li class="nav-item">
            <a class="nav-link" href="{{ route('addProduct') }}">Add Product</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('addCategory') }}">Add Category</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('orders') }}">Orders</a>
          </li>

        @endif

      </ul>

      <!-- RIGHT -->
      <ul class="navbar-nav ms-auto">

        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Sign In</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
          </li>
        @endguest

        @auth
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="btn nav-link text-white border-0 bg-transparent">
                Logout
              </button>
            </form>
          </li>
        @endauth

      </ul>

    </div>

  </div>
</nav>

