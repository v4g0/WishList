<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\product;
use App\wish;

class WishList extends Controller
{
    //
    function createNewWish(Request $r){
      $r->validate([
          'name' => 'required|unique:products|max:255',
          'description' => 'required|max:255',
          'price' => 'required|numeric',
          'image' => 'image|mimes:jpeg,bmp,png,jpg',
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

      return response()->json(['success' => true]);

    }
}
