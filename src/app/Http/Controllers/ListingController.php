<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Listing;

class ListingController extends Controller
{
    public function index(Request $request) {
        $listings =   Listing::where('is_active', true)
        ->with('tags')
        ->latest()
        ->get();

        $tags = \App\Models\Tag::orderBy('name')->get();

        if($request->has('s')) {
            $query = strtolower($request->get('s'));
            $listings = $listings->filter(function($listing) use($query) {
                if(Str::contains(strtolower($listing->title), $query)) {
                    return true;
                }
                if(Str::contains(strtolower($listing->company), $query)) {
                    return true;
                }
                if(Str::contains(strtolower($listing->location), $query)) {
                    return true;
                }
                if(Str::contains(strtolower($listing->content), $query)) {
                    return true;
                }

                return false;
            });
        }
        if($request->has('tag')) {
            $tag = $request->get('tag');
            $listings = $listings->filter(function($listing) use($tag) {
                return $listing->tags->contains('slug', $tag);

            });
        }
        // return $listings;
        return view('listings.index', compact('listings','tags'));

    }


    public function show(Listing $listing, Request $request) {

        return view('listings.show', compact('listing'));
        // return $listing;
    }

    public function apply(Listing $listing, Request $request) {

      $listing->clicks()->create([
          'user_agent' => $request->userAgent(),
          'ip' => $request->ip()
      ]);

      return redirect()->to($listing->apply_link);
    }

    // public function yes(Request $request) {
    //     return "yes";
    // }
}