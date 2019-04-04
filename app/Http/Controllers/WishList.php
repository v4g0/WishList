<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\product;
use App\wish;
use App\User;
use Mail;

class WishList extends Controller
{
    //
    function updateWishList(Request $r){
      $r->validate([
          'id' => 'required|numeric',
          'name' => 'required|max:255',
          'description' => 'required|max:255',
          'price' => 'required|numeric',
          'image' => 'image|mimes:jpeg,bmp,png,jpg',
      ]);
      //get wish for have product id
      $wish = wish::find($r->id);
      //check if wish exist
      if(!isset($wish->id)){
        return response()->json(['success'=> false]);
      }
      //get product and edit
      $product = product::find($wish->product_id);
      $product->name = $r->name;
      $product->description = $r->description;
      $product->price = $r->price;

      if($r->hasfile('image')){
        $name= md5(date('Y-m-d h:i:s') .mt_rand() ) .'.'. $r->image->getClientOriginalExtension();
        $OriginalImage= Image::make($r->image)->orientate();;
        $thumbnailImage = Image::make($r->image)->orientate();
        $originalPath = public_path().'/uploads/products/' . $name;
        $thumbnailPath = public_path().'/uploads/products/thumbnail/' . $name;

        $OriginalImage->resize(null, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $OriginalImage->save($originalPath);

        $thumbnailImage->fit(370, 413, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailImage->save($thumbnailPath);

        $product->image  = $name;
      }

      //save product's changes
      $product->save();

      return response()->json(['success'=> true]);
    }
    function removeFromWishList(Request $r){
      $r->validate([
          'id' => 'required|numeric',
      ]);

      //Check if the user is the owner of the wish and get it.
      $wish = wish::where(['user_id' => $r->user()->id , 'id' => $r->id])->first();
      if(!isset($wish->id)){
        return response()->json(['success'=> false]);
      }

      //change wish's status for remove from list
      $wish->status_ = 0;
      $wish->save();

      return response()->json(['success'=> true]);
    }

    function getBoughtList(Request $r){
      //Get products that user bought
      $wishList = User::find($r->user()->id)
      ->wishList()
      ->select('name','price')
      ->where('bought' , 1)
      ->orderby('wishes.updated_at' , 'desc')
      ->get();

      //check if exist almost 1 product
      if(!isset($wishList[0])){
        return response()->json(['success'=> false]);
      }

      return response()->json(['success' => true , 'data' => $wishList]);
    }

    function searchProduct(Request $r){
      $r->validate([
          'search' => 'required|min:1',
      ]);

      $wishList = User::find($r->user()->id)
      ->wishList()
      ->select('wishes.id','image','name','bought','description','price','products.created_at','products.updated_at')
      ->where('status_' , 1)
      ->where('user_id', $r->user()->id)
      ->where('name', 'LIKE', '%'.$r->search.'%')
      ->orWhere('status_' , 1)
      ->where('user_id', $r->user()->id)
      ->where('description', 'LIKE', '%'.$r->search.'%')
      ->orderby('created_at' , 'desc')
      ->groupby('id')
      ->get();

      //check if exist almost 1 product in wishlist
      if(!isset($wishList[0])){
        return response()->json(['success'=> false]);
      }
      return response()->json(['success' => true , 'data' => $wishList]);
    }

    function getWishList(Request $r){
      $r->validate([
          'type' => 'required|numeric|between:0,2',
      ]);

      //Find products of the wish list
      if($r->type == 2){
        $wishList = User::find($r->user()->id)
        ->wishList()
        ->select('wishes.id','image','name','bought','description','price','products.created_at','products.updated_at')
        ->where('status_' , 1)
        ->orderby('created_at' , 'desc')
        ->get();
      }else{
        $wishList = User::find($r->user()->id)
        ->wishList()
        ->select('wishes.id','image','name','bought','description','price','products.created_at','products.updated_at')
        ->where(['status_' =>1 , 'bought' => $r->type])
        ->orderby('created_at' , 'desc')
        ->get();
      }
      //check if exist almost 1 product in wishlist
      if(!isset($wishList[0])){
        return response()->json(['success'=> false]);
      }
      return response()->json(['success' => true , 'data' => $wishList]);
    }

    function createNewWish(Request $r){
      $r->validate([
          'name' => 'required|max:255',
          'description' => 'required|max:255',
          'price' => 'required|numeric',
          'image' => 'required|image|mimes:jpeg,bmp,png,jpg',
      ]);

      //Create new product
      $product = new product;
      $product->name = $r->name;
      $product->description = $r->description;
      $product->price = $r->price;

      //Save image for product and create a thumbnail for list view
      if($r->hasfile('image')){
        $name= md5(date('Y-m-d h:i:s') .mt_rand() ) .'.'. $r->image->getClientOriginalExtension();
        $OriginalImage= Image::make($r->image)->orientate();;
        $thumbnailImage = Image::make($r->image)->orientate();
        $originalPath = public_path().'/uploads/products/' . $name;
        $thumbnailPath = public_path().'/uploads/products/thumbnail/' . $name;

        $OriginalImage->resize(null, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $OriginalImage->save($originalPath);

        $thumbnailImage->fit(370, 413, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbnailImage->save($thumbnailPath);

        $product->image  = $name;
      }

      $product->save();

      //Add new product to user's WishList
      $wish = new wish;
      $wish->user_id = $r->user()->id;
      $wish->product_id = $product->id;
      $wish->status_ = 1;
      $wish->bought = 0;

      $wish->save();

      //Send email for user
      $email = $r->user()->email;
      $data_mail['name'] =  $r->user()->name;
      $data_mail['subject'] =  'Nuevo deseo añadido.';
      $data_mail['msg'] =  'El deseo: '. $product->name . ' fue añadido satisfactoriamente.' ;
      $data_mail['date'] = date('Y-m-d h:i:s');

      Mail::send('email.default', $data_mail, function($message) use($email) {
         $message->to($email)->subject
            ('Nuevo deseo añadido.');
         $message->from('wishlist.demobybrihuega@gmail.com','Wish List By David Brihuega');
      });
      return response()->json(['success' => true]);

    }
}
