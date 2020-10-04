<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // check if there's an access token for this user to fetch their posts from a resource server
        $userToken = auth()->user()->userOAuthToken;
        $userPosts = [];
        
        if($userToken !== null) {
            // make request to fetch posts with the token
            if($request->user()->userOAuthToken->hasTokenExpired()) {
                return redirect('/dashboard/oauth/approve_request');
            }
            $resourceResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $userToken->access_token
            ])->get(env('RESOURCE_APP_URL') . 'api/user/resource/posts');
             
            if($resourceResponse->status() === 200) {
                $userPosts = $resourceResponse->json();
            }
            
        }

        return view('dashboard', compact('userPosts'));
    }

    public function approveRequest(Request $request)
    {
        
        $sessionState = $request->session()->put('state', Str::random(40));
          
        $query = http_build_query([
            'client_id' => env('CLIENT_ID'),
            'redirect_uri' => env('APP_URL') . 'dashboard/oauth/callback',
            'response_type' => 'token',
            'scope' => 'view-posts',
            'state' => $sessionState,
        ]);
        return redirect(env('RESOURCE_APP_URL') . 'oauth/authorize?' . $query);
    }

    public function requestCallback(Request $request)
    {    
     
      
        if ($request->user()->userOAuthToken) {
            $request->user()->userOAuthToken()->delete();
        }
        
        $request->user()->userOAuthToken()->create([
            'access_token' => $request->access_token,
            'expires_in' => $request->expires_in
        ]);

        return redirect('/dashboard');

    }

  
}
