<?php

namespace App\Http\Controllers;

use App\Models\Link_interaction;
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

    public static function goto(Request $request, $id) {
        try {
            $shortlink = Shortlink::where('shortid', $id)->first();
            // check if the link is expired or if it has reached the max clicks
            if ($shortlink->expires_at != null && strtotime($shortlink->expires_at) < time()) {
                return response()->json(['error' => 'This link has expired'], 404);
            }

            if ($shortlink->max_clicks != null && $shortlink->max_clicks <= (new Link_interaction())->getTotalInteractions($id)) {
                return response()->json(['error' => 'This link has reached the maximum number of clicks'], 404);
            }

            // log the interaction
            [Link_interactionController::class, 'access']($request);

            return redirect($shortlink->destination);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getDetails(Request $request, $id) {
        try {
            $shortlink = Shortlink::where('shortid', $id)->first();
            if ($shortlink->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $countrylist = (new Link_interactionController)->getCountryArray($id);
            return view('details', ['shortlink' => $shortlink, 'countrylist' => $countrylist]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id) {
        try {
            $shortlink = Shortlink::where('shortid', $id)->first();
            if ($shortlink->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $request['expiry-toggle'] = $request->input('expiry-toggle') == 'on';
            $data = $request->validate([
                'maxclicks' => 'required|integer|min:0',
                'expiry-toggle' => 'required|boolean'
            ]);
            if ($request->input('expiry-toggle')) {
                $request->validate([
                    'expiry-date' => 'date_format:Y-m-d|required',
                    'expiry-hour' => 'date_format:H|min:0|max:23|required',
                    'expiry-minute' => 'date_format:i|min:0|max:59|required'
                ]);
                $request->expires_at = \DateTime::createFromFormat('Y-m-d H:i', $request['expiry-date'] . ' ' . $request['expiry-hour'] . ':' . $request['expiry-minute']);
            } else {
                $request->expires_at = null;
            }
            $shortlink->modify($request->maxclicks, $request->expires_at);
            return back();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function delete(Request $request, $id) {
        try {
            $shortlink = Shortlink::where('shortid', $id)->first();
            $shortlink->delete();
            return redirect('profile');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getLinksByUser(Request $request) {
        if (auth()->user() == null) {
            return redirect('home');
        }
        $shortlinks = Shortlink::where('user_id', auth()->id())->get();
        return view('profile', ['shortlinks' => $shortlinks]);
    }
}
