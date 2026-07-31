<x-layout>

<style>

/* PAGE */
body { background: #f5f6f8; padding-top: 80px; }

/* TITLE */
.cart-title {
  font-weight: bold;
}

/* CARD */
.cart-box {
  border-radius: 15px;
}

/* ITEM */
.cart-item {
  padding: 15px;
  border-bottom: 1px solid #eee;
  transition: 0.2s;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cart-item:hover {
  background: #fafafa;
}

/* IMAGE */
.cart-item img {
  width: 80px;
  height: 80px;
  object-fit: contain;
  border-radius: 10px;
  background: #f8f9fa;
  padding: 5px;
  flex-shrink: 0;
}

/* INFO */
.cart-info h6 {
  margin: 0;
  font-size: 1rem;
  word-break: break-word;
}

.cart-info .price {
  color: #28a745;
  font-weight: 600;
}

/* QUANTITY CONTROLS CONTAINER */
.cart-actions {
  display: flex;
  align-items: center;
}

/* QUANTITY DESIGN */
.quantity-control {
  display: flex;
  align-items: center;
  border: 1px solid #dee2e6;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.quantity-control button {
  width: 42px;
  height: 42px;
  border: none;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s ease;
}

/* minus */
.minus-btn {
  background: #f1f3f5;
}

.minus-btn:hover {
  background: #e2e6ea;
}

/* plus */
.plus-btn {
  background: #d4edda;
  color: #28a745;
}

.plus-btn:hover {
  background: #c3e6cb;
}

/* click */
.quantity-control button:active {
  transform: scale(0.9);
}

/* disabled */
.quantity-control button:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

/* number */
.quantity-control span {
  width: 50px;
  text-align: center;
  font-weight: 600;
}

/* REMOVE BUTTON */
.remove-btn {
  border: none;
  background: #ffe5e5;
  color: #dc3545;
  border-radius: 10px;
  padding: 8px 12px;
  margin-left: 12px;
  transition: 0.2s;
  height: 42px;
}

.remove-btn:hover {
  background: #ffcccc;
  transform: scale(1.05);
}

/* TOTAL BAR */
.checkout-bar {
  position: sticky;
  bottom: 0;
  background: #fff;
  border-top: 2px solid #eee;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 1000;
}

/* 📱 MOBILE RESPONSIVE ADJUSTMENTS */
@media (max-width: 576px) {
  body {
    padding-top: 20px;
  }

  .cart-item {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }

  .cart-actions {
    justify-content: flex-end;
    width: 100%;
  }

  .checkout-bar {
    gap: 12px;
    padding: 12px 20px;
  }

  .checkout-bar #total {
    font-size: 1.1rem;
  }

  .checkout-bar .btn {
    padding: 8px 16px;
    font-size: 0.95rem;
  }
}

</style>

<div class="container my-4 my-md-5">
<h2 class="text-center mb-4 cart-title">🛍️ Your Cart</h2>

@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

@if (empty($cart) || count($cart) == 0)
    <p class="text-center">Your cart is empty. <a href="/">Start shopping!</a></p>
@else

<div class="card cart-box shadow-sm p-2 p-md-3">

{{-- USER --}}
@if(Auth::check())
@foreach ($cart as $item)
<div id="cart-item-{{ $item->product_id }}"
     class="cart-item"
     data-stock="{{ $item->product->quantity }}">

    <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('storage/' . $item->product->main_img) }}">
        <div class="cart-info">
            <h6>{{ $item->product->name }}</h6>
            <div class="price">${{ $item->product->price }}</div>
        </div>
    </div>

    <div class="cart-actions">
        <div class="quantity-control">
            <button class="minus-btn" onclick="changeQuantity({{ $item->product_id }}, -1)">−</button>
            <span class="qty">{{ $item->quantity }}</span>
            <button class="plus-btn" onclick="changeQuantity({{ $item->product_id }}, 1)">+</button>
        </div>

        <button class="remove-btn" onclick="removeItem({{ $item->product_id }})">✕</button>
    </div>
</div>
@endforeach

{{-- GUEST --}}
@else
@foreach ($products as $product)
@php $qty = $cart[$product->id]['quantity']; @endphp

<div id="cart-item-{{ $product->id }}"
     class="cart-item"
     data-stock="{{ $product->quantity }}">

    <div class="d-flex align-items-center gap-3">
        <img src="{{ asset('storage/' . $product->main_img) }}">
        <div class="cart-info">
            <h6>{{ $product->name }}</h6>
            <div class="price">${{ $product->price }}</div>
        </div>
    </div>

    <div class="cart-actions">
        <div class="quantity-control">
            <button class="minus-btn" onclick="changeQuantity({{ $product->id }}, -1)">−</button>
            <span class="qty">{{ $qty }}</span>
            <button class="plus-btn" onclick="changeQuantity({{ $product->id }}, 1)">+</button>
        </div>

        <button class="remove-btn" onclick="removeItem({{ $product->id }})">✕</button>
    </div>
</div>
@endforeach
@endif

</div>
@endif
</div>

@if (!empty($cart) && count($cart) > 0)
<div class="checkout-bar container">
    <div id="total" class="h5 mb-0">Total: $0.00</div>
    <a href="/checkout" class="btn btn-success">Proceed to Checkout</a>
</div>
@endif

<script>

// TOTAL
function recalcTotal(){
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(item => {
        let qty = parseInt(item.querySelector('.qty').textContent);
        let price = parseFloat(item.querySelector('.price').textContent.replace('$',''));
        total += qty * price;
    });
    document.getElementById('total').innerText = "Total: $" + total.toFixed(2);
}

// DISABLE +
function updateButtons(){
    document.querySelectorAll('.cart-item').forEach(row => {
        let qty = parseInt(row.querySelector('.qty').textContent);
        let stock = parseInt(row.getAttribute('data-stock'));
        let plusBtn = row.querySelector('.plus-btn');

        plusBtn.disabled = qty >= stock;
    });
}

// CHANGE QTY
function changeQuantity(id, delta){
    let row = document.getElementById('cart-item-'+id);
    let qtyEl = row.querySelector('.qty');

    let currentQty = parseInt(qtyEl.textContent);
    let stock = parseInt(row.getAttribute('data-stock'));
    let newQty = currentQty + delta;

    if(newQty < 1) return;

    if(newQty > stock){
        alert("Max stock reached");
        return;
    }

    qtyEl.textContent = newQty;

    fetch('/changeQuan/' + id, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ quantity: newQty })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'error'){
            alert(data.message);
            qtyEl.textContent = currentQty;
        }
    });

    recalcTotal();
    updateButtons();
}

// REMOVE
function removeItem(id){
    fetch('/remove/' + id, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
    .then(() => {
        document.getElementById('cart-item-'+id).remove();
        recalcTotal();

        if(document.querySelectorAll('.cart-item').length === 0) {
            document.querySelector('.cart-box').innerHTML =
                '<p class="text-center my-3">Your cart is empty. <a href="/">Start shopping!</a></p>';
            document.querySelector('.checkout-bar').remove();
        }
    });
}

// INIT
recalcTotal();
updateButtons();

</script>

</x-layout>
