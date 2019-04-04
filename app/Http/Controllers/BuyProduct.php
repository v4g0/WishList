<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\product;
use App\wish;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

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

     $email = $r->user()->email;
     $data_mail['name'] =  $r->user()->name;
     $data_mail['subject'] =  'Deseo descontado del presupuesto.';
     $data_mail['msg'] =  'El deseo: '. $product->name . ' fue descontado del presupuesto.' ;
     $data_mail['date'] = date('Y-m-d h:i:s');

     Mail::send('email.default', $data_mail, function($message) use($email) {
        $message->to($email)->subject
           ('Deseo comprado.');
        $message->from('wishlist.demobybrihuega@gmail.com','Wish List By David Brihuega');
     });

     return response()->json(['success'=> true ]);

    }
}
