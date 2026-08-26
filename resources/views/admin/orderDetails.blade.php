<style>
body { background: #f5f6f8; }

/* CARD */
.order-card {
  border-radius: 15px;
}

/* TITLE */
.section-title {
  font-weight: 600;
  margin-bottom: 15px;
}

/* PRODUCT IMAGE */
.product-img {
  width: 70px;
  height: 70px;
  object-fit: contain;
  background: #f8f9fa;
  border-radius: 10px;
  padding: 5px;
}

/* TABLE */
.table th {
  background: #000;
  color: #fff;
}

/* STATUS */
.status {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  display: inline-block;
  white-space: nowrap;
}

.status.pending { background: #ffc107; }
.status.completed { background: #28a745; color: #fff; }
.status.cancelled { background: #dc3545; color: #fff; }

/* RESPONSIVE FIXES */
@media (max-width: 992px) {
  .col-lg-4, .col-lg-8 {
    margin-bottom: 1rem;
  }
}

@media (max-width: 768px) {
  .product-img {
    width: 45px;
    height: 45px;
  }

  .table {
    font-size: 0.75rem;
  }

  .table td, .table th {
    padding: 0.5rem;
  }

  .section-title {
    font-size: 1.1rem;
    margin-bottom: 10px;
  }

  .card {
    padding: 1rem !important;
  }

  h2 {
    font-size: 1.5rem;
  }

  p, .card p {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
  }

  .status {
    font-size: 0.7rem;
    padding: 3px 8px;
  }

  .container {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }
}

@media (max-width: 576px) {
  .table thead {
    display: none;
  }

  .table, .table tbody, .table tr, .table td {
    display: block;
    width: 100%;
  }

  .table tr {
    margin-bottom: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: white;
    padding: 0.5rem;
  }

  .table td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem;
    border-bottom: 1px solid #dee2e6;
  }

  .table td:last-child {
    border-bottom: none;
  }

  .table td::before {
    content: attr(data-label);
    font-weight: bold;
    width: 40%;
    color: #000;
  }

  .product-img {
    width: 40px;
    height: 40px;
  }

  .d-flex {
    flex-direction: column;
    align-items: flex-end;
  }

  h5 {
    font-size: 1rem;
  }

  .text-success {
    font-size: 1rem;
  }
}

@media (max-width: 400px) {
  .product-img {
    width: 35px;
    height: 35px;
  }

  .table td {
    font-size: 0.7rem;
    padding: 0.4rem;
  }

  .table td::before {
    font-size: 0.7rem;
  }

  .card {
    padding: 0.75rem !important;
  }
}

/* FIX: Hide data-label content on desktop */
@media (min-width: 577px) {
  .table td::before {
    display: none !important;
  }
}
</style>

<x-layout>

<div class="container my-5">

  <h2 class="text-center mb-4 fw-bold">📦 Order Details</h2>

  <div class="row g-4">

    <!-- LEFT: CUSTOMER INFO -->
    <div class="col-lg-4">
      <div class="card order-card shadow-sm p-3">

        <div class="section-title">Customer Info</div>

        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>

      </div>

      <!-- ORDER STATUS -->
      <div class="card order-card shadow-sm p-3 mt-3">

        <div class="section-title">Order Info</div>

        <p><strong>Order ID:</strong> #{{ $order->id }}</p>

        <p>
          <strong>Status:</strong>
          <span class="status {{ $order->status }}">
            {{ ucfirst($order->status) }}
          </span>
        </p>

        <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>

      </div>
    </div>

    <!-- RIGHT: PRODUCTS -->
    <div class="col-lg-8">
      <div class="card order-card shadow-sm p-3">

        <div class="section-title">Products</div>

        <div class="table-responsive">
          <table class="table align-middle">

            <thead>
              <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
              </tr>
            </thead>

            <tbody>
              @php $totalPrice = 0; @endphp

              @foreach($order->items as $item)
                @php $totalPrice += $item->price * $item->quantity; @endphp
                <tr>
                  <td data-label="Image">
                    <img src="{{ asset('storage/' . $item->product->main_img) }}"
                         class="product-img"
                         alt="{{ $item->product->name }}"
                         onerror="this.src='{{ asset('images/default-product.png') }}'">
                  </td>
                  <td data-label="Product">{{ $item->product->name }}</td>
                  <td data-label="Price">${{ number_format($item->price, 2) }}</td>
                  <td data-label="Qty">{{ $item->quantity }}</td>
                  <td data-label="Total">
                    ${{ number_format($item->price * $item->quantity, 2) }}
                  </td>
                </tr>
              @endforeach

            </tbody>

          </table>
        </div>

        <hr>

        <!-- TOTAL -->
        <div class="d-flex justify-content-end">
          <h5>
            Total:
            <span class="text-success">
              ${{ number_format($totalPrice, 2) }}
            </span>
          </h5>
        </div>

      </div>
    </div>

  </div>

</div>

</x-layout>
