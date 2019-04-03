<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\product;
use App\wish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuyProduct extends Controller
{
    //
    public function __invoke(Request $r)
    {
      $r->validate([
          'id' => 'required|numeric',
      ]);

      //Check if the user is the owner of the wish and get it.
      $wish = wish::where(['user_id' => $r->user()->id , 'id' => $r->id , 'bought' => 0])->first();
      if(!isset($wish->id)){
        return response()->json(['success'=> false , 'msg' => 'product']);
      }

      //get Product price
      $product = product::find($wish->product_id);

      //get user
      $user = Auth::user();

      //verify if the user has a suffcient budget
      if($user->budget < $product->price){
        return response()->json(['success'=> false , 'msg' => 'budget']);
      }

      /*
      start transaction for subtract product's price to user's budget
      and change wish to bought
      */
     try{
       DB::transaction(function() use($user,$wish,$product) {
         $user->budget = $user->budget - $product->price;
         $user->save();
         $wish->bought = 1;
         $wish->save();
       });
     } catch (\Exception $e) {
        return response()->json(['success'=> false , 'msg' => 'transaction']);
     }

     return response()->json(['success'=> true ]);

    }
}
