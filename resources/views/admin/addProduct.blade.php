<x-layout>
<div class="container my-5">
    <h2>Add New Product</h2>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/addProduct" enctype="multipart/form-data">
        @csrf

        <!-- NAME -->
        <input type="text" name="name"
               class="form-control mb-2"
               placeholder="Name"
               value="{{ old('name') }}">

        <!-- PRICE -->
        <input type="number" name="price"
               class="form-control mb-2"
               placeholder="Price"
                step="any"  
               value="{{ old('price') }}">

        <!-- QUANTITY -->
        <input type="number" name="quantity"
               class="form-control mb-2"
               placeholder="Quantity"
               value="{{ old('quantity') }}">

        <!-- IMAGE -->
        <input type="file" name="main_img" class="form-control mb-2">

        <!-- CATEGORY -->
        <select name="category_id" class="form-control mb-2">
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <!-- DESCRIPTION -->
        <textarea name="description"
                  class="form-control mb-2"
                  placeholder="Description">{{ old('description') }}</textarea>

        <!-- BUTTON -->
        <button class="btn btn-success">Add Product</button>
    </form>
</div>
</x-layout>
