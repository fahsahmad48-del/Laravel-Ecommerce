<x-layout>

<div class="bg-light p-5 text-center">
    <h1 class="fw-bold">Add New Category</h1>
    <p class="lead">Create a new category to organize your products</p>
  </div>

  <!-- Add Category Form -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h4 class="mb-4 text-center">Category Information</h4>
            <form action="/addCategory" method="POST" >
                @csrf
              <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter category name">
              </div>
              <button type="submit" class="btn btn-success w-100">Add Category</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


</x-layout>
