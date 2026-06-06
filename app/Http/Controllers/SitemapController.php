<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Part;
use App\Models\Rental;
use App\Models\Vehicle;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // statik sayfalar
        foreach (['home', 'vehicles.index', 'shop.index', 'rentals.index', 'blog.index', 'pages.about', 'pages.contact'] as $name) {
            $urls[] = ['loc' => route($name), 'priority' => $name === 'home' ? '1.0' : '0.8'];
        }

        // araçlar
        foreach (Vehicle::published()->get(['slug', 'updated_at']) as $v) {
            $urls[] = ['loc' => route('vehicles.show', $v->slug), 'lastmod' => $v->updated_at, 'priority' => '0.7'];
        }
        // parçalar
        foreach (Part::published()->get(['slug', 'updated_at']) as $p) {
            $urls[] = ['loc' => route('shop.show', $p->slug), 'lastmod' => $p->updated_at, 'priority' => '0.6'];
        }
        // kiralık
        foreach (Rental::published()->get(['slug', 'updated_at']) as $r) {
            $urls[] = ['loc' => route('rentals.show', $r->slug), 'lastmod' => $r->updated_at, 'priority' => '0.6'];
        }
        // blog
        foreach (Post::published()->get(['slug', 'updated_at']) as $post) {
            $urls[] = ['loc' => route('blog.show', $post->slug), 'lastmod' => $post->updated_at, 'priority' => '0.6'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= '  <url><loc>' . e($u['loc']) . '</loc>';
            if (! empty($u['lastmod'])) {
                $xml .= '<lastmod>' . $u['lastmod']->toAtomString() . '</lastmod>';
            }
            $xml .= '<priority>' . $u['priority'] . '</priority></url>' . "\n";
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
