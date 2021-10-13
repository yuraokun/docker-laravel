<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\ParsedownExtra;

class ListingController extends Controller
{
    public function index(Request $request) {
        $listings =   Listing::where('is_active', true)
        ->where('del_flg', 0)
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

    public function create() {
        return view('listings.create');

    }

    public function store(Request $request) {

        $validationArray = [
            'title' => 'required',
            'company' => 'required',
            'logo' => 'file|max:2048',
            'location' => 'required',
            'apply_link' => 'required|url',
            'content' => 'required',
            'payment_method_id' => 'required'
        ];
        if(!Auth::check()) {
            $validationArray = array_merge($validationArray,[
                'email' => 'required|email|unique:users',
                'password'=> 'required|confirmed|min:5',
                'name' => 'required'
            ]);
        }

        $request->validate($validationArray);

        $user = Auth::user();
        if(!$user) {
            $user = \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password)
            ]);
            $user->createAsStripeCustomer();
        }

        

        Auth::login($user);

        try {
            $amount = 9900;
            if($request->filled('is_highlighted')) {
                $amount += 1900;
            }

            $user->charge($amount, $request->payment_method_id);

            $md = new \ParsedownExtra();

            $listing = $user->listings()->create([

                'title' => $request->title,
                'company' => $request->company,
                'slug' => Str::slug($request->title).'-'.rand(1111, 9999),
                'logo' => basename($request->file('logo')->store('public')),
                'location' => $request->location,
                'apply_link' => $request->apply_link,
                'content' => $md->text($request->content),
                'is_highlighted' => $request->filled('is_highlighted'),
                'is_active' => true

            ]);

            foreach(explode(',', $request->tags) as $requestTag) {
                $tag = \App\Models\Tag::firstOrCreate([
                    'slug' => Str::slug(trim($requestTag))
                ], ['name' => ucwords(trim($requestTag))]);
                $tag->listings()->attach($listing->id);
            }

            return redirect()->route('dashboard');
        } catch(Exception $e) {
            return redirect()->back()->withErrors(['errors' => $e->getMessage()]);
        }




        

    }


    public function getLogout(){
        Auth::logout();
        return redirect()->route('listings.index');
    }

    // public function yes(Request $request) {
    //     return "yes";
    // }
}