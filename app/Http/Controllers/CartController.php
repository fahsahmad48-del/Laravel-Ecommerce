<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{

    // Change quantity of a cart item via AJAX and JSON request body , save to DB
    function changeQuantity(Request $request, int $id) { // php dont take type for $id , but we can use it for better code readability and to avoid errors

    if(Auth::check()){
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $id)
                        ->first(); // we can use firstOrFail() but it will throw an exception if the item is not found, we want to return a JSON response instead

        if ($cartItem) {
            $qtty = $request->quantity;
            $OriginalQtty = Product::find($id)->quantity;
            if($qtty > $OriginalQtty){
                return response()->json(['status' => 'error', 'message' => 'Not enough stock available']);
            }
            $cartItem->quantity = $qtty; // read from JSON body
            $cartItem->save(); // important to save the changes to the database
            return response()->json(['status' => 'success']); // return JSON response
        }
        return response()->json(['status' => 'error', 'message' => 'Cart item not found']);
    }

    $cart = session()->get('cart', []); // get cart from session or empty array if not set
    if (isset($cart[$id])) {
        $qtty = $request->quantity;
        $OriginalQtty = Product::find($id)->quantity;
        if($qtty > $OriginalQtty){
            return response()->json(['status' => 'error', 'message' => 'Not enough stock available']);
        }
        $cart[$id]['quantity'] = $qtty; // read from JSON body
        session()->put('cart', $cart);
        return response()->json(['status' => 'success']); // return JSON response
    }

    return response()->json(['status' => 'error', 'message' => 'Cart item not found']);

    }


    function removeFromCart(int $id){

    if(Auth::check()){
        $userId = Auth::id();
        Cart::where('user_id', $userId)
            ->where('product_id', $id)
            ->delete();
        return response()->json(['status' => 'success']);
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart); // update the cart in session after removing the item (important!)
        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'error', 'message' => 'Cart item not found']);

    }


 function addToCart(int $id){

    $userId = Auth::id();

    if (!$userId) {
        $cart = session()->get('cart', []);
      /*  if (!in_array($id, $cart)) {
            $cart[] = $id;
            session()->put('cart', $cart);
        } */

       if (!isset($cart[$id])) {
            $cart[$id] = [
                'product_id' => $id,
                'quantity' => 1
            ];
        }

        session()->put('cart', $cart);
        session()->flash('added', true); // Set a flash message to indicate that the product was added to the cart
        return response()->json([
            'status' => 'success'
        ]);

    }

    $cartItem = Cart::where('user_id', $userId)
                    ->where('product_id', $id)
                    ->first();

    if (!$cartItem) {
        Cart::create([
            'user_id' => $userId,
            'product_id' => $id,
            'quantity' => 1
        ]);
    }

    session()->flash('added', true);

    return response()->json([
        'status' => 'success'
    ]);

    }

    function cart() {

    if (!Auth::check()) {
        $cart = session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();
        return view('cart', compact('cart', 'products'));
    }

    $userId = Auth::id();
    // Load the cart items WITH their related products
    // we can dont use with but the load of the page will be slow
    $cart = Cart::with('product') // if we dont use with, we will have to do a query for each cart item to get the product data, which will be very slow if we have many cart items
                ->where('user_id', $userId)
                ->get();

    return view('cart', compact('cart'));
}
}
