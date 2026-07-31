<x-layout>

<style>

/* PAGE */
body {
  background: #f8f9fb;
  padding-top: 90px; /* space for fixed navbar */
}

/* LAYOUT */
.auth-wrapper {
  max-width: 900px;
  margin: auto;
  min-height: calc(100vh - 90px);
  display: flex;
  align-items: center;
}

/* LEFT SIDE */
.auth-left {
  padding: 60px 40px;
}

.brand {
  font-weight: 700;
  font-size: 1.3rem;
  margin-bottom: 40px;
}

.auth-heading {
  font-size: 1.8rem;
  font-weight: 600;
  margin-bottom: 10px;
}

.auth-sub {
  color: #6c757d;
  margin-bottom: 30px;
}

/* FORM */
.form-control {
  border-radius: 8px;
  padding: 12px;
  border: 1px solid #e2e5e9;
}

.form-control:focus {
  border-color: #000;
  box-shadow: none;
}

/* BUTTON */
.btn-auth {
  background: #000;
  color: #fff;
  border-radius: 8px;
  padding: 12px;
  font-weight: 500;
}

.btn-auth:hover {
  background: #111;
}

/* RIGHT SIDE */
.auth-right {
  background: #111;
  color: #fff;
  border-radius: 16px;
  padding: 60px 40px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.auth-right h3 {
  font-weight: 600;
  margin-bottom: 15px;
}

.auth-right p {
  color: #bbb;
}

/* LINKS */
.auth-links a {
  text-decoration: none;
  color: #555;
  font-size: 0.9rem;
}

.auth-links a:hover {
  color: #000;
}

/* 📱 RESPONSIVE FIXES FOR MOBILE */
@media (max-width: 768px) {
  body {
    padding-top: 20px !important;
  }

  .auth-wrapper {
    min-height: auto !important; /* Stop vertical stretch on mobile */
    align-items: flex-start !important; /* Align to top */
    padding-top: 10px;
    padding-bottom: 30px;
  }

  .auth-right {
    display: none;
  }

  .auth-left {
    padding: 30px 20px !important;
  }

  .brand {
    margin-bottom: 20px !important;
  }

  .auth-sub {
    margin-bottom: 20px !important;
  }
}

</style>

<div class="container auth-wrapper">
  <div class="row g-0 shadow-sm rounded-4 overflow-hidden bg-white w-100">

    <!-- LEFT -->
    <div class="col-md-6 auth-left">

      <div class="brand">MiniStore</div>
      <div class="auth-heading">Sign in</div>
      <div class="auth-sub">Access your account and continue shopping</div>

      <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
          <input type="email"
                 name="email"
                 value="{{ old('email') }}"
                 class="form-control @error('email') is-invalid @enderror"
                 placeholder="Email address">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <input type="password"
                 name="password"
                 class="form-control @error('password') is-invalid @enderror"
                 placeholder="Password">
          @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-auth w-100 mt-2">
          Sign In
        </button>

      </form>

      <div class="mt-4 auth-links">
        Don’t have an account?
        <a href="/register" class="fw-semibold text-dark">Sign up</a>
      </div>

    </div>

    <!-- RIGHT -->
    <div class="col-md-6 auth-right">
      <h3>Welcome back</h3>
      <p>
        Manage your orders, track deliveries, and explore new products
        all in one place.
      </p>
    </div>

  </div>
</div>

</x-layout>
