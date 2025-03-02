<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'shortid',
        'destination',
        'user_id',
        'max_clicks',
        'expires_at',
        'deleted'
    ];

    public function create(string $url, int $user_id): Shortlink {
        $this->shortid = $this->generateNewId();
        $this->destination = $url;
        $this->user_id = $user_id;
        $this->max_clicks = 0;
        $this->expires_at = null;
        $this->deleted = false;
        $this->save();
        return $this;
    }

    public function delete(): void {
        Shortlink::where('shortid', $this->id)->delete();
    }

    public function modify(int $max_clicks, $expires_at): void {
        $this->max_clicks = $max_clicks;
        $this->expires_at = $expires_at;
        $this->save();
    }

    function generateNewId(int $length = 5): string {
        $characters = 'qwrtypsdfghjklzxcvbnmQWRTYPSDFGHJKLZXCVBNM256789_';
        // try n x 2 times to generate a new id, if it finds an id, return it, otherwise, try to look for an id of length n + 1
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
