<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class LinkController extends Controller
{
    public function home()
    {
        $newLink = Cache::get('newLink');
        $qrCodePath = Cache::get('qrCodePath');
        return view('home', compact('newLink', 'qrCodePath'));
    }

    public function get($shortUrl)
    {
        $fullShortUrl = 'localhost:8000/' . $shortUrl;
        $link = Link::where('short_url', $fullShortUrl)->first();

        if (!$link) {
            return redirect()->back()->with('error', 'URL not found');
        }

        if ($link->expired_at && now()->greaterThan($link->expired_at)) {
            return redirect()->back()->with('error', 'URL has expired');
        }

        return redirect($link->original_url);
    }

    public function create(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url:http,https',
            'expired_at' => 'nullable|date'
        ]);

        $originalUrl = $request->input('original_url');

        $shortUrl = $this->generateShortUrl('localhost:8000');
        while (Link::where('short_url', $shortUrl)->exists()) {
            $shortUrl = $this->generateShortUrl('localhost:8000');
        }

        $expiredAt = $request->filled('expired_at') ? $request->input('expired_at') : now()->addMinutes(30);

        try {
            $newLink = Link::create([
                'original_url' => $originalUrl,
                'short_url' => $shortUrl,
                'expired_at' => $expiredAt
            ]);
            Cache::put('newLink', $newLink, now()->addMinutes(5));
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function generateRandomString($length)
    {
        $allowedCharacters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = Str::random($length);
        $filteredString = str_shuffle(str_replace(str_split($allowedCharacters), '', $randomString));
        return substr_replace($randomString, $filteredString, 0, strlen($filteredString));
    }

    private function generateShortUrl($domain)
    {
        return $domain . '/' . $this->generateRandomString(8);
    }
}
