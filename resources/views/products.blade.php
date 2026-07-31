<x-layout>

<!-- HEADER -->
<div class="bg-light p-5 text-center">
  <h1 class="fw-bold">Our Products</h1>
  <p class="lead">Browse our collection of amazing products</p>
</div>

<!-- SEARCH -->
<div class="container my-4">
  <div class="row g-3">

    <div class="col-md-6 col-12">
      <input type="text" id="keyword"
             class="form-control"
             placeholder="Search products...">
    </div>

    <div class="col-md-4 col-12">
      <select id="category" class="form-select">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>

  </div>
</div>

<!-- LOADING -->
<div id="loading" class="text-center my-4 d-none">
  <div class="spinner-border text-primary"></div>
  <p class="mt-2 text-muted">Loading products...</p>
</div>

<!-- PRODUCTS -->
<div class="container my-5">
  <div class="row g-4" id="productGrid">

    {{-- INITIAL LOAD --}}
    @foreach ($products as $product)
      <div class="col-md-3 col-sm-6 col-12">
        <div class="card h-100 shadow-sm border-0 rounded-4">

          <div style="height:200px; display:flex; align-items:center; justify-content:center;">
            <img src="/storage/{{ $product->main_img }}"
                 style="object-fit:contain; max-height:100%; max-width:100%;">
          </div>

          <div class="card-body d-flex flex-column">
            <h5 class="product-title">{{ $product->name }}</h5>
            <p class="text-muted">${{ number_format($product->price, 2) }}</p>

            @if(in_array($product->id, $cartItems))
              <button class="btn btn-success mt-auto w-100" disabled>Added</button>
            @else
              <button onclick="addToCart({{ $product->id }})"
                      id="btn-{{ $product->id }}"
                      class="btn btn-primary mt-auto w-100">
                Add to Cart
              </button>
            @endif

          </div>
        </div>
      </div>
    @endforeach

  </div>
</div>

<style>
/* MOBILE FIX */
@media (max-width: 768px) {
  .bg-light { padding: 2rem 1rem !important; }
  .bg-light h1 { font-size: 1.75rem; }
  .product-title { font-size: 1rem; }
}

@media (max-width: 576px) {
  .bg-light { padding: 1.5rem 1rem !important; }
  .bg-light h1 { font-size: 1.5rem; }
  .product-title { font-size: 0.9rem; }
  .btn { font-size: 0.85rem; }
}
</style>

<script>

// LOAD PRODUCTS
function loadProducts(){

    let keyword = document.getElementById("keyword").value;
    let category = document.getElementById("category").value;

    let loading = document.getElementById("loading");
    let grid = document.getElementById("productGrid");

    loading.classList.remove("d-none");

    let url = `/products/filter?keyword=${encodeURIComponent(keyword)}`; // encodeURIComponent to handle special characters (e.g., spaces) in the keyword
    if(category){
        url += `&category=${category}`;
    }

    fetch(url)
    .then(async res => {

        let text = await res.text(); // DEBUG
        console.log("SERVER:", text);

        if (!res.ok) {
            throw new Error("Server error");
        }

        return JSON.parse(text);
    })
    .then(data => {

        let products = data.products || []; // it means "if data.products exists use it, otherwise use an empty array"
        let cartItems = data.cartItems || [];

        grid.innerHTML = "";

        if (products.length === 0) {
            grid.innerHTML = `
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">No products found</h4>
                </div>
            `;
            return;
        }

        products.forEach(product => {

            let inCart = cartItems.includes(product.id);

            grid.innerHTML += `
              <div class="col-md-3 col-sm-6 col-12">
                <div class="card h-100 shadow-sm border-0 rounded-4">

                  <div style="height:200px; display:flex; align-items:center; justify-content:center;">
                    <img src="/storage/${product.main_img}"
                         style="object-fit:contain; max-height:100%; max-width:100%;">
                  </div>

                  <div class="card-body d-flex flex-column">
                    <h5>${product.name}</h5>
                    <p class="text-muted">$${parseFloat(product.price).toFixed(2)}</p>

                    ${
                      inCart
                      ? `<button class="btn btn-success mt-auto w-100" disabled>Added</button>`
                      : `<button onclick="addToCart(${product.id})"
                                 id="btn-${product.id}"
                                 class="btn btn-primary mt-auto w-100">
                           Add to Cart
                         </button>`
                    }

                  </div>
                </div>
              </div>`;
        });

    })
    .catch(err => {
        console.error(err);

        grid.innerHTML = `
            <div class="col-12 text-center py-5">
                <h4 class="text-danger">⚠️ Server error</h4>
            </div>
        `;
    })
    .finally(() => {
        loading.classList.add("d-none");
    });
}


// AUTO SEARCH
let timeout;

document.getElementById("keyword").addEventListener("input", () => {
    clearTimeout(timeout);
    timeout = setTimeout(loadProducts, 400);
});

document.getElementById("category").addEventListener("change", loadProducts);


// ADD TO CART
function addToCart(id){
  fetch('/addToCart/' + id, {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
    }
  })
  .then(res => res.json())
  .then(result => {
    if(result.status === "success"){
        let btn = document.getElementById("btn-"+id);
        if(btn){
            btn.innerText = "Added";
            btn.disabled = true;
            btn.classList.replace("btn-primary","btn-success");
        }
    }
  });
}


//  LOAD CATEGORY FROM URL + LOAD PRODUCTS 
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const categoryFromURL = params.get("category");

    if (categoryFromURL) {
        document.getElementById("category").value = categoryFromURL;
    }

    loadProducts();
});

</script>

</x-layout>
