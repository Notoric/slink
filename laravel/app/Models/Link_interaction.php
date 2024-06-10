<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//TODO
class Link_interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'ip',
        'latitude',
        'longitude',
        'country',
        'country_code'
    ];

    public function create(string $link, string $ip): Link_interaction {
        $this->link = $link;
        $this->ip = $ip;

        $url = "http://ip-api.com/json/$ip?fields=49347";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $data = json_decode($response, true);
        
        if ($data['status'] == 'fail') {
            $this->latitude = null;
            $this->longitude = null;
            $this->country = null;
            $this->country_code = null;
            $this->save();
            return $this;
        }

        $this->latitude = $data['lat'] ?? null;
        $this->longitude = $data['lon'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->country_code = $data['countryCode'] ?? null;

        $this->save();
        return $this;
    }

    public function getRecords(string $link) {
        $link_interaction = [];
        $link_interaction = Link_interaction::where('link', $link)->get()->toArray();
        return $link_interaction;
    }

    public function getCount(string $link) {
        $link_interaction = [];
        $link_interaction = Link_interaction::where('link', $link)->count();
        return $link_interaction;
    }

    public function getCountryArray(string $link) {
        $link_interaction = [];
        $link_interaction = Link_interaction::where('link', $link)->select('country', Link_interaction::raw('count(*) as total'), 'country_code')->groupBy('country', 'country_code')->get()->toArray();
        $country_list = [];
        foreach ($link_interaction as $country) {
            if ($country['country_code'] == null) {
                $country['emoji'] = '❓';
            } else {
                $country['emoji'] = preg_replace_callback('/./', static fn (array $letter) => mb_chr(ord($letter[0]) % 32 + 0x1F1E5), $country['country_code']);
            }
            array_push($country_list, $country);
        }
        return $country_list;
    }

    public function getTotalInteractions(string $link) : int {
        $link_interaction = Link_interaction::where('link', $link)->count();
        return $link_interaction;
    }

    public function getTimes(string $link) {
        $link_interaction = [];
        $link_interaction = Link_interaction::where('link', $link)->select('created_at')->get()->toArray();
        return $link_interaction;
    }

    public function getCoordinates(string $link) {
        $link_interaction = [];
        $link_interaction = Link_interaction::where('link', $link)->select('latitude', 'longitude')->get()->toArray();
        return $link_interaction;
    }

}
