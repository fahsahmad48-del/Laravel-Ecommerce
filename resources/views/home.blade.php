<x-layout>

<!-- HERO -->
<div class="hero-section d-flex align-items-center justify-content-center text-center text-white">
  <div>
    <h1 class="fw-bold display-4">Welcome to MyShop</h1>
    <p class="lead mb-4">Find your best products with amazing deals</p>
    <a href="/products" class="btn btn-lg btn-light fw-semibold px-4">Shop Now</a>
  </div>
</div>

<!-- WHY CHOOSE US -->
<div class="container my-5 text-center">
  <div class="row g-4">

    <div class="col-md-4">
      <div class="p-4 shadow-sm rounded bg-white h-100">
        <h5>🚚 Fast Delivery</h5>
        <p class="text-muted">Quick delivery to your doorstep</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-4 shadow-sm rounded bg-white h-100">
        <h5>💳 Secure Payment</h5>
        <p class="text-muted">100% safe and secure payments</p>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-4 shadow-sm rounded bg-white h-100">
        <h5>⭐ Top Quality</h5>
        <p class="text-muted">Only the best products for you</p>
      </div>
    </div>

  </div>
</div>

<!-- CATEGORIES -->
<div class="container my-5">
  <h2 class="text-center mb-4 fw-bold">Shop by Category</h2>

  <div class="row g-3 text-center">
    @foreach($categories as $cat)
      <div class="col-md-3 col-6">
        <a href="/products?category={{ $cat->id }}" class="text-decoration-none">
          <div class="p-4 bg-white shadow-sm rounded category-card">
            <h6 class="mb-0">{{ $cat->name }}</h6>
          </div>
        </a>
      </div>
    @endforeach
  </div>
</div>

<!-- PRODUCTS -->
<div class="container my-5">
  <h2 class="text-center mb-5 fw-bold">Featured Products</h2>

  <div class="row g-4">
    @forelse ($products as $product)

      <div class="col-lg-3 col-md-4 col-sm-6 reveal">
        <div class="card product-card h-100 border-0 shadow-sm">

          <div class="img-container">
            <img src="{{ asset('storage/' . $product->main_img) }}">
          </div>

          <div class="card-body d-flex flex-column">
            <h6 class="fw-semibold">{{ $product->name }}</h6>
            <p class="text-muted mb-2">{{ Str::limit($product->description, 50) }}</p>
            <p class="text-muted mb-3">${{ number_format($product->price, 2) }}</p>

            @if (in_array($product->id, $cartItems))
              <button class="btn btn-success mt-auto" disabled>Added ✔</button>
            @else
              <button id="btn-{{ $product->id }}"
                      onclick="addToCart({{ $product->id }})"
                      class="btn btn-dark mt-auto add-btn">
                Add to Cart
              </button>
            @endif

          </div>
        </div>
      </div>

    @empty
      <p class="text-center text-muted">No products available</p>
    @endforelse
  </div>
</div>

<!-- CTA -->
<div class="cta-section text-center text-white py-5">
  <h2 class="fw-bold">Ready to start shopping?</h2>
  <a href="/products" class="btn btn-light mt-3 px-4">Browse Products</a>
</div>

<!-- STYLES -->
<style>

/* HERO */
.hero-section {
  height: 60vh;
  background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
              url('https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=1920');
  background-size: cover;
  background-position: center;
}

/* CATEGORY */
.category-card {
  transition: 0.3s;
}
.category-card:hover {
  transform: translateY(-5px);
}

/* PRODUCT */
.product-card {
  border-radius: 15px;
  transition: 0.3s;
  overflow: hidden;
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.img-container {
  height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff; /* Updated to white background */
}

.img-container img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

/* CTA */
.cta-section {
  background: #000;
}

/* SCROLL ANIMATION */
.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: 0.6s;
}

.reveal.active {
  opacity: 1;
  transform: translateY(0);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .img-container { height: 150px; }
}

</style>

<!-- JS -->
<script>

// ADD TO CART
function addToCart(id){
  let btn = document.getElementById("btn-"+id);
  btn.innerText = "Adding...";
  btn.disabled = true;

  fetch('/addToCart/' + id, {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": "{{ csrf_token() }}",
      "Content-Type": "application/json"
    }
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === "success"){
      btn.innerText = "Added ✔";
      btn.classList.remove("btn-dark");
      btn.classList.add("btn-success");
    } else {
      btn.innerText = "Add to Cart";
      btn.disabled = false;
    }
  });
}

// SCROLL ANIMATION
document.addEventListener("DOMContentLoaded", function () {
  const reveals = document.querySelectorAll(".reveal");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("active");
      }
    });
  }, { threshold: 0.2 });

  reveals.forEach(el => observer.observe(el));
});

</script>

</x-layout>
