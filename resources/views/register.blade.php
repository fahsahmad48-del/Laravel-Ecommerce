<x-layout>

<style>

/* PAGE */
body {
  background: #f8f9fb;
}

/* CARD */
.auth-card {
  border-radius: 15px;
  overflow: hidden;
}

/* TITLE */
.auth-title {
  font-weight: 600;
}

/* INPUT */
.form-control {
  border-radius: 8px;
  padding: 12px;
}

.form-control:focus {
  box-shadow: none;
  border-color: #000;
}

/* BUTTON */
.btn-auth {
  border-radius: 8px;
  padding: 12px;
  font-weight: 500;
  background: #000;
  color: #fff;
}

.btn-auth:hover {
  background: #111;
}

/* SMALL TEXT */
.auth-footer {
  font-size: 0.9rem;
}

/* SHOW PASSWORD */
.toggle-pass {
  cursor: pointer;
  position: absolute;
  right: 15px;
  top: 12px;
  color: #777;
  z-index: 5;
}

.password-wrapper .form-control {
  padding-right: 45px;
}

/* 📱 FORCE TOP ALIGNMENT ON MOBILE */
@media (max-width: 576px) {
  /* Override any flex centering forced by x-layout parent */
  main, .auth-wrapper {
    display: block !important;
    align-items: flex-start !important;
    justify-content: flex-start !important;
    min-height: auto !important;
    padding-top: 10px !important;
  }

  .auth-container {
    margin-top: 0 !important;
    margin-bottom: 20px !important;
    padding-top: 0 !important;
  }

  .auth-card .card-body {
    padding: 1.25rem !important;
  }
}

</style>

<div class="auth-wrapper w-100">
  <div class="container auth-container my-auto">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 col-lg-5">

        <div class="card auth-card shadow-lg">
          <div class="card-body p-4">

            <h3 class="mb-4 text-center auth-title">Create Account</h3>

            <form method="POST" action="/register">
              @csrf

              <!-- NAME -->
              <div class="mb-3">
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Full Name">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- EMAIL -->
              <div class="mb-3">
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email address">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- PASSWORD -->
              <div class="mb-3 position-relative password-wrapper">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Password">
                <span class="toggle-pass" onclick="togglePassword()">👁</span>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- CONFIRM -->
              <div class="mb-3">
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Confirm Password">
              </div>

              <!-- BUTTON -->
              <button type="submit" class="btn btn-auth w-100">
                Sign Up
              </button>

            </form>

            <!-- FOOTER -->
            <div class="text-center mt-3 auth-footer">
              Already have an account?
              <a href="/login" class="text-dark fw-semibold">Sign in</a>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
function togglePassword(){
    let input = document.getElementById('password');
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</x-layout>
