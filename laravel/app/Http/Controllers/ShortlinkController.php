<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortlink;
use GuzzleHttp\Client;

class ShortlinkController extends Controller
{
    public function create(Request $request) {
        if (auth()->user() == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $request->validate([
                'url' => 'required|url'
            ]);
    
            //check if url returns 200 at its final redirect
            $guzzle = new Client([
                'timeout' => 5
            ]);
            try {
                $response = $guzzle->get($request->url, ['allow_redirects' => ['track_redirects' => true]]);
                if ($response->getStatusCode() != 200) {
                    return back()->withErrors([
                        'error' => 'The URL provided did not return a valid response'
                    ]);
                }
            } catch (\Exception $e) {
                return back()->withErrors([
                    'error' => 'The URL provided did not return a valid response'
                ]);
            }

            $shortlink = new Shortlink();
            $shortlink->create($request->url, auth()->id());
            return redirect("/l/{$shortlink->shortid}");
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function goto(Request $request, $id) {
        try {
            $shortlink = (new Shortlink())->get($id);
            // check if the link is expired or if it has reached the max clicks
            // log the interaction
            return redirect($shortlink->destination);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
