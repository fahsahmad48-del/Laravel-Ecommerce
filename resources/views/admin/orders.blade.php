<x-layout>

<!-- IMPORTANT CSRF -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
body { background: #f5f6f8; }

/* CARD */
.orders-card {
  border-radius: 15px;
}

/* TABLE */
.table thead {
  background: #000;
  color: white;
}

.table td, .table th {
  vertical-align: middle;
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

.status.pending { background: #ffc107; color: #000; }
.status.completed { background: #28a745; color: #fff; }
.status.cancelled { background: #dc3545; color: #fff; }

/* RESPONSIVE FIXES */
@media (max-width: 768px) {
  .table {
    font-size: 0.75rem;
  }

  .table td, .table th {
    padding: 0.5rem;
  }

  .btn-sm {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
  }

  .form-select-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
  }

  .status {
    font-size: 0.7rem;
    padding: 3px 8px;
  }

  h2 {
    font-size: 1.5rem;
  }

  .container {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }

  .card {
    padding: 1rem !important;
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
  }

  .table td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
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

  .btn-sm {
    margin-bottom: 0.5rem;
  }

  .form-select-sm {
    width: auto;
    margin-left: 0.5rem;
  }
}
</style>

<div class="container my-5">

  <h2 class="fw-bold mb-4 text-center">📦 Orders Management</h2>

  <div class="card orders-card shadow-sm p-3">

    <div class="table-responsive">
      <table class="table align-middle">

        <thead>
          <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>



          @forelse($orders as $order)
          <tr id="row-{{ $order->id }}">

            <td data-label="#">{{ $order->id }}</td>
            <td data-label="Customer">{{ $order->name }}</td>
            <td data-label="Email">{{ $order->email }}</td>
            <td data-label="Total"> ${{ number_format($ordertotal, 2) }}</td>

            <!-- STATUS BADGE -->
            <td data-label="Status">
              <span id="status-badge-{{ $order->id }}" class="status {{ $order->status }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>

            <td data-label="Date">{{ $order->created_at->format('Y-m-d') }}</td>

            <td data-label="Actions">
              <!-- VIEW -->
              <a href="{{ url('/admin/orderDetails/'.$order->id) }}"
                 class="btn btn-sm btn-dark">
                View
              </a>

              <!-- SELECT -->
              <select onchange="updateStatus({{ $order->id }}, this)"
                      class="form-select form-select-sm mt-1">

                <option disabled selected>Change</option>

                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                  Pending
                </option>

                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>
                  Completed
                </option>

                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                  Cancelled
                </option>

              </select>
            </td>

          </tr>

          @empty
          <tr>
            <td colspan="7" class="text-center py-4">
              No orders found
            </td>
          </tr>
          @endforelse

        </tbody>

       </table>
    </div>

  </div>

</div>

<script>
function updateStatus(id, selectEl){

  let status = selectEl.value;
  if(!status) return;

  fetch('/admin/orders/status/' + id, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ status: status })
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === "success"){

      //  update badge without reload
      let badge = document.getElementById("status-badge-" + id);
      badge.className = "status " + status;
      badge.innerText = status.charAt(0).toUpperCase() + status.slice(1);

    } else {
      alert("Error updating status");
    }
  })
  .catch(() => alert("Server error"));
}
</script>

</x-layout>
