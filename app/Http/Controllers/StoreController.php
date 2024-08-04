<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('store.index', compact('products'));
    }

    public function addToCart($id)
    {
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "description" => $product->description,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('store.index')->with('success', 'Product added to cart!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('store.viewCart')->with('success', 'Product removed from cart!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('store.cart', compact('cart'));
    }
}
