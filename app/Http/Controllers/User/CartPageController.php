<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

use App\Models\Coupon;

class CartPageController extends Controller
{
  public function MyCart()
  {
    return view('frontend.wishlist.view_mycart');
  }

  public function GetCartProduct()
  {
    $carts = Cart::content();
    $cartQty = Cart::count();
    $cartTotal = Cart::total();

    return response()->json(array(
      'carts' => $carts,
      'cartQty' => $cartQty,
      // 'cartTotal' => round($cartTotal),
      'cartTotal' => $cartTotal,
    ));
  }

  public function RemoveCartProduct($rowId)
  {
    Cart::remove($rowId);
    return response()->json(['success' => 'Successfully Remove From Cart']);
  }

  // Cart Increment 
  public function CartIncrement($rowId)
  {
    $row = Cart::get($rowId);
    Cart::update($rowId, $row->qty + 1);

    if (Session::has('coupon')) {

      $coupon_name = Session::get('coupon')['coupon_name'];
      $coupon = Coupon::where('coupon_name', $coupon_name)->first();

      Session::put('coupon', [
        'coupon_name' => $coupon->coupon_name,
        'coupon_discount' => $coupon->coupon_discount,
        'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
        'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)
      ]);
    }
    return response()->json('increment');
  }

  // Cart Decrement 
  public function CartDecrement($rowId)
  {
    $row = Cart::get($rowId);
    Cart::update($rowId, $row->qty - 1);

    if (Session::has('coupon')) {

      $coupon_name = Session::get('coupon')['coupon_name'];
      $coupon = Coupon::where('coupon_name', $coupon_name)->first();

      Session::put('coupon', [
        'coupon_name' => $coupon->coupon_name,
        'coupon_discount' => $coupon->coupon_discount,
        'discount_amount' => round(Cart::total() * $coupon->coupon_discount / 100),
        'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount / 100)
      ]);
    }
    return response()->json('increment');
  }

}
