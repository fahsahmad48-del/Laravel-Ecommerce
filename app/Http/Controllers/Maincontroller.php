<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Orders;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Maincontroller extends Controller
{

    function placeOrder(Request $request) {

        // 1. Get cart items
        if (Auth::check()) {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $cart = session()->get('cart', []);
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id'); // keyBy means we can access products by their ID, e.g. $products[1] for product with ID 1
            $cartItems = [];
            foreach ($cart as $id => $item) {
                if (isset($products[$id])) {
                    $cartItems[] = (object)[
                        'product' => $products[$id],
                        'quantity' => $item['quantity']
                    ];
                }
            }
        }

        // 2. Create order and order items logic here (not implemented in this snippet)
           if (Auth::check()) {
            // Create order for authenticated user
            $order = Orders::create([
                'user_id' => Auth::id(),
                'email' => $request->email,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'pending',
                'payment_method' =>  $request->payment_method ? $request->payment_method : 'cash_on_delivery'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'email' => $request->email,
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'payment_method' =>  $request->payment_method ? $request->payment_method :'cash_on_delivery'
                ]);
            }

        } else {
            // Create order for guest user (you might want to handle this differently)
            $order = Orders::create([
                'user_id' => null,
                'email' => $request->email,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'pending',
                'payment_method' =>  $request->payment_method ? $request->payment_method :'cash_on_delivery'

            ]);
        }

        // 3. Clear cart after placing order
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('cart')
         ->with('success', 'Order placed successfully!');

    }

    /* =========================
       HOME PAGE
    ========================= */
    public function home()
    {
        $products = Product::latest()->take(8)->get();
        $categories = Category::all();

        //  INLINE cart items
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $cartItems = array_keys(session()->get('cart', []));
        }

        return view('home', compact('products', 'categories', 'cartItems'));
    }

    /* =========================
       PRODUCTS PAGE
    ========================= */
    public function productsPage()
    {
        $categories = Category::all();
        $products = Product::all();

        //  INLINE cart items
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $cartItems = array_keys(session()->get('cart', []));
        }

        return view('products', compact('categories', 'products', 'cartItems'));
    }

    /* =========================
       ADD PRODUCT (ADMIN)
    ========================= */
    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'main_img' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // STORE IMAGE
        $imagePath = $request->file('main_img')->store('products', 'public');

        //  CREATE PRODUCT
        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'main_img' => $imagePath
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }



    /* =========================
       search products
    ========================= */

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();

        //  INLINE cart items
        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        } else {
            $cart = session()->get('cart', []);
            $cartItems = array_keys($cart);
        }

        return response()->json([
            'products' => $products,
            'cartItems' => $cartItems
        ]);
    }

    /* =========================
       ADD TO CART
    ========================= */
    public function addToCart(int $id)
    {
        if (Auth::check()) {

            Cart::firstOrCreate( // Check if the cart item already exists for the user and product , if it does, it will return the existing item; if not, it will create a new one.
                [
                    'user_id' => Auth::id(),
                    'product_id' => $id
                ],
                [
                    'quantity' => 1
                ]
            );

        } else {

            $cart = session()->get('cart', []);

            if (!isset($cart[$id])) {
                $cart[$id] = [
                    'product_id' => $id,
                    'quantity' => 1
                ];
            }

            session()->put('cart', $cart);
        }

        return response()->json(['status' => 'success']);
    }

    /* =========================
       REMOVE FROM CART
    ========================= */
    public function removeFromCart(int $id)
    {
        if (Auth::check()) {

            Cart::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->delete();

        } else {

            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['status' => 'success']);
    }

    /* =========================
       UPDATE QUANTITY
    ========================= */
    public function changeQuantity(Request $request,int $id)
    {
        $quantity = $request->quantity;

        if (Auth::check()) {

            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->first();

            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }

        } else {

            $cart = session()->get('cart', []);

            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
            }
        }

        return response()->json(['status' => 'success']);
    }

    /* =========================
       CHECKOUT
    ========================= */
    public function checkout()
{
    if (Auth::check()) {

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

         if($cartItems->isEmpty()) {
            return redirect()->route('cart');
        }


    } else {

        $cart = session()->get('cart', []);

         if (empty($cart)) {
            return redirect()->route('cart');
        }

        $productIds = array_keys($cart);

        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $cartItems = [];

        foreach ($cart as $id => $item) {
            if (isset($products[$id])) {
                $cartItems[] = (object)[
                    'product' => $products[$id],
                    'quantity' => $item['quantity']
                ];
            }
        }
    }

    return view('checkout', compact('cartItems'));
}
}
