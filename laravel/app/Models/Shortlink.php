<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'shortid',
        'destination',
        'user_id',
        'max_clicks',
        'expires_at',
    ];

    public function create(string $url, int $user_id): Shortlink {
        $this->shortid = $this->generateNewId();
        $this->destination = $url;
        $this->user_id = $user_id;
        $this->max_clicks = 0;
        $this->expires_at = null;
        $this->save();
        return $this;
    }

    public function get(string $id): Shortlink {
        $shortlink = Shortlink::where('shortid', $id)->first();
        if ($shortlink == null) {
            throw new \Exception('This shortened link does not exist');
        }
        return $shortlink;
    }

    public function delete(): void {
        Shortlink::where('shortid', $this->id)->delete();
    }

    public function modify(int $max_clicks, $expires_at): void {
        $this->max_clicks = $max_clicks;
        $this->expires_at = $expires_at;
        $this->save();
    }

    function generateNewId(int $length = 6): string {
        $characters = 'qwrtypsdfghjklzxcvbnmQWRTYPSDFGHJKLZXCVBNM256789_';
        // try n x 2 times to generate a new id, if it finds an id, return it, otherwise, try to look for an id of length + 1
        for ($i = 0; $i < $length * 2; $i++) {
            $id = '';
            for ($i = 0; $i < $length; $i++) {
                $id .= $characters[rand(0, strlen($characters) - 1)];
            }
            if (Shortlink::where('shortid', $id)->count() == 0) {
                return $id;
            }
        }
        return $this->generateNewId($length + 1);
    }
}
