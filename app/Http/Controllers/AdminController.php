<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Orders;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{

   function orderDetails(int $id) {
    $order = Orders::with('items.product')->findOrFail($id);
   return view('admin.orderDetails', compact('order'));
   }


    public function updateOrderStatus(Request $request,int $id) {

    $order = Orders::findOrFail($id); // if fail it will throw a 404 error
    $order->status = $request->input('status'); // Update the status based on the request input
    $order->save(); // important to save the changes to the database

    return response()->json([
        'status' => 'success'
    ]);}

    function orderPage() {
        $orders = Orders::latest()->get(); //  means we want to order the results by the created_at column in descending order, so that the most recent orders appear first.
        //order total , but there is no total column in the orders table, so we need to calculate it by summing up the total of each order.
        $ordertotal = 0;
        foreach ($orders as $order) {
            $ordertotal += $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });
        }

        return view('admin.orders', compact('orders', 'ordertotal'));
    }

    // ===== PRODUCT =====

    function addProductPage() {
        $categories = Category::all();
        return  view('admin.addProduct', compact('categories'));
    }

    function addProduct(Request $request) {

    $fields = $request->validate([
        'name' => ['required','string','max:255'],
        'price' => ['required','numeric','min:0','between:0,9999.99'],
        'quantity' => ['required','integer','min:0'],
        'description' => ['required','string'],
        'main_img' => ['required','image','mimes:jpeg,png,jpg,gif,webp','max:2048'],
        'category_id' => ['required','exists:categories,id']
    ]);

    // Store image
    $image = $request->file('main_img');
    $filename = time() . '.' . $image->getClientOriginalExtension();
    $image->storeAs('products', $filename, 'public');

    // Save image path in DB
    $fields['main_img'] = 'products/' . $filename;

    Product::create($fields);

    return redirect('/')->with('success', 'Product added successfully!');
}

    // ===== CATEGORY =====
    function addCategoryPage() {
        return view('admin.addCategory');
    }

    function addCategory(Request $request) {
        $fields = $request->validate([
            'name' => ['required','string','max:255']
        ]);

        Category::create($fields);

        return redirect()->route('addCategory')
            ->with('success', 'Category added successfully!');
    }




}
