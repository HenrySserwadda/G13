<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Models\CartModel;

class CartController extends Controller
{
    public function cart(){
        $cartContent=CartModel::content;
        return view('cart');
    }
    public function addToCart(Request $request){
        $product=Product::with('product_images')->find($request->id);//this had a relationship method in te products model
        //if product is already in cart then notify the user, if its not then add it. Add a feedbck message for a user to know that the product has been added to the cart
        CartModel::add($product->id,$product->name,);//product quantity,price
    }

    
    public function removeFromCart(){

    }
}
