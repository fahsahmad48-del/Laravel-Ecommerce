<x-layout>

<style>
body { background: #f5f6f8; }

.checkout-card {
  border-radius: 15px;
  overflow: hidden;
}

.card-header {
  font-weight: 600;
}

.form-control, .form-select {
  border-radius: 10px;
  padding: 10px;
}

.form-control:focus, .form-select:focus {
  box-shadow: none;
  border-color: #000;
}

.section-title {
  font-weight: 600;
  margin-bottom: 15px;
}

.place-btn {
  border-radius: 10px;
  padding: 12px;
  font-weight: 600;
  transition: 0.3s;
}

.place-btn:hover {
  transform: scale(1.02);
}

.summary-item {
  font-size: 0.95rem;
}

.total-box {
  font-size: 1.2rem;
}

@media (max-width: 768px) {
  .checkout-card { margin-bottom: 20px; }
}

@media (max-width: 576px) {
  .card-body { padding: 15px; }
  .place-btn { font-size: 0.9rem; }
}
</style>

<div class="container my-5">

  <div class="row g-4">

    <!-- LEFT: FORM -->
    <div class="col-lg-7">
      <div class="card checkout-card shadow-sm">

        <div class="card-header bg-dark text-white">
          Checkout
        </div>

        <div class="card-body">

          <form method="POST" action="{{ route('placeOrder') }}">
            @csrf

            <!-- BILLING -->
            <div class="section-title">Billing Information</div>

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="name"
                     value="{{ old('name') }}"
                     class="form-control @error('name') is-invalid @enderror"
                     placeholder="John Doe">
              @error('name')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email"
                     value="{{ old('email') }}"
                     class="form-control @error('email') is-invalid @enderror"
                     placeholder="you@example.com">
              @error('email')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Phone Number</label>
              <input type="text" name="phone"
                     value="{{ old('phone') }}"
                     class="form-control @error('phone') is-invalid @enderror"
                     placeholder="+123456789">
              @error('phone')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Shipping Address</label>
              <textarea name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        rows="3"
                        placeholder="123 Main St...">{{ old('address') }}</textarea>
              @error('address')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <!-- PAYMENT -->
            <div class="section-title mt-4">Payment Details</div>

            <div class="mb-3">
              <label class="form-label">Payment Method</label>
              <select name="payment_method"
                      class="form-select @error('payment_method') is-invalid @enderror">

                <option value="">Select Payment Method</option>
                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                  Cash on Delivery
                </option>

              </select>

              @error('payment_method')
                <div class="text-danger small">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success w-100 place-btn">
              Place Order
            </button>

          </form>

        </div>
      </div>
    </div>

    <!-- RIGHT: SUMMARY -->
    <div class="col-lg-5">
      <div class="card checkout-card shadow-sm">

        <div class="card-header bg-dark text-white">
          Order Summary
        </div>

        <div class="card-body">

          @if(count($cartItems) == 0)
            <p class="text-center text-muted">Your cart is empty</p>
          @else

            @php $total = 0; @endphp

            @foreach ($cartItems as $item)
              <div class="d-flex justify-content-between summary-item mb-2">
                <div>
                 <span style="font-weight: bold">{{ $item->product->name }} x ({{ $item->quantity }})</span>
                </div>
                <div>
                  ${{ number_format($item->product->price * $item->quantity, 2) }}
                </div>
              </div>

              @php
                $total += $item->product->price * $item->quantity;
              @endphp
            @endforeach

            <hr>

            <div class="d-flex justify-content-between total-box">
              <strong>Total</strong>
              <strong>${{ number_format($total, 2) }}</strong>
            </div>

          @endif

        </div>
      </div>
    </div>

  </div>
</div>

</x-layout>
