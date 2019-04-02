<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Budget extends Controller
{
    //
    function getBudget(Request $r){
      return response()->json([
        'budget' => $r->user()->budget,
        'name' => $r->user()->name
      ]);
    }

    function updateBudget(Request $r){
      $r->validate([
          'budget' => 'required|numeric',
      ]);
      //get user
      $user = Auth::user();
      $user->budget = $r->budget;
      $user->save();

      return response()->json(['success'=> true ]);
    }
}
