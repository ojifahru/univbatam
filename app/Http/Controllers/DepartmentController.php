<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DepartmentController extends Controller
{
    public function show($slug)
    {
        $locale = App::getLocale();

        $department = Departement::where("slug->$locale", $slug)->first();

        if (!$department) {
            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $department = Departement::where("slug->$localeCode", $slug)->first();
                if ($department) break;
            }
        }

        if (!$department) abort(404);

        $department->load('faculty');

        $pageTitle = $department->getTranslation('name', $locale, false);

        $visi = $misi = $description = $email = $alamat = $no_telp = null;
        $news = [];

        $baseUrl = rtrim($department->department_link, '/');

        $httpOptions = [
            'verify' => false,
            'timeout' => 10,
        ];

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ];

        // Fetch visi & misi
        try {
            $response = Http::withOptions($httpOptions)->withHeaders($headers)->get("$baseUrl/index.php/api/getVisiMisi");
            if ($response->successful()) {
                foreach ($response->json() as $item) {
                    if (($item['id_kategori'] ?? '') === "1") $visi = $item['isi_visimisi'];
                    if (($item['id_kategori'] ?? '') === "2") $misi = $item['isi_visimisi'];
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching visi misi: ' . $e->getMessage());
        }

        // Fetch description
        try {
            $response = Http::withOptions($httpOptions)->withHeaders($headers)->get("$baseUrl/index.php/api/getAbout");
            $aboutData = $response->json();

            if (is_array($aboutData)) {
                if (isset($aboutData[0]['isi_about'])) {
                    $description = $aboutData[0]['isi_about'];
                } elseif (isset($aboutData['isi_about'])) {
                    $description = $aboutData['isi_about'];
                } else {
                    foreach ($aboutData as $val) {
                        if (is_string($val) && strlen($val) > 50) {
                            $description = $val;
                            break;
                        }
                    }
                }
            } elseif (is_string($aboutData)) {
                $description = $aboutData;
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching about: ' . $e->getMessage());
        }

        // Fetch news
        try {
            $response = Http::withOptions($httpOptions)->withHeaders($headers)->get("$baseUrl/index.php/api/getNews");
            if ($response->successful()) $news = $response->json();
        } catch (\Exception $e) {
            \Log::error('Error fetching news: ' . $e->getMessage());
        }

        // Fetch identity
        try {
            $response = Http::withOptions($httpOptions)->withHeaders($headers)->get("$baseUrl/index.php/api/getIdentity");
            $identityData = $response->json();
            if (!empty($identityData[0])) {
                $identity = $identityData[0];
                $email = $identity['email'] ?? null;
                $alamat = $identity['alamat'] ?? null;
                $no_telp = $identity['no_telp'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching identity: ' . $e->getMessage());
        }

        return view('pages.department', compact(
            'department',
            'pageTitle',
            'visi',
            'misi',
            'description',
            'news',
            'baseUrl',
            'email',
            'alamat',
            'no_telp'
        ));
    }

    public function proxyImage(Request $request, $imagePath)
    {
        $departmentUrl = $request->query('dept_url');
        if (empty($departmentUrl)) abort(400, 'Missing department URL parameter');

        $imageUrl = (strpos($imagePath, '/') !== false)
            ? rtrim($departmentUrl, '/') . '/' . $imagePath
            : rtrim($departmentUrl, '/') . '/asset/foto_berita/' . $imagePath;

        try {
            \Log::info('Proxying image from', ['url' => $imageUrl]);

            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 10,
                'allow_redirects' => true,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get($imageUrl);

            if (!$response->successful()) {
                \Log::error('Failed to proxy image: ' . $response->status());
                return response()->file(public_path('images/placeholder.jpg'));
            }

            return response($response->body())
                ->header('Content-Type', $response->header('Content-Type', 'image/jpeg'))
                ->header('Cache-Control', 'public, max-age=86400');
        } catch (\Exception $e) {
            \Log::error('Proxy image exception: ' . $e->getMessage());
            return response()->file(public_path('images/placeholder.jpg'));
        }
    }
}
