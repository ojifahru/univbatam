<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking untuk admin routes, API, assets
        if (
            str_starts_with($request->path(), 'admin') ||
            str_starts_with($request->path(), 'api') ||
            str_starts_with($request->path(), 'assets') ||
            str_starts_with($request->path(), 'storage') ||
            str_starts_with($request->path(), '_ignition') ||
            $request->is('livewire/*') ||
            $request->ajax()
        ) {
            return $next($request);
        }

        // Hanya track GET requests
        if ($request->method() === 'GET') {
            $userAgent = $request->userAgent() ?: '';
            $agent = new Agent();
            
            // Deteksi bot menggunakan model Visitor
            $botInfo = Visitor::detectBot($userAgent);
            $isBot = $botInfo['is_bot'];
            $botName = $botInfo['bot_name'];
            
            // Jika bot dideteksi oleh Agent library juga
            if ($agent->isRobot()) {
                $isBot = true;
                $botName = $botName ?: $agent->robot();
            }

            // Track bot tanpa mengecek visit terbaru
            if ($isBot) {
                Visitor::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'page_visited' => $request->fullUrl(),
                    'referer_url' => $request->headers->get('referer'),
                    'country' => null, // Tidak perlu geo-lokasi untuk bot
                    'device_type' => 'bot', // Tandai tipe perangkat sebagai bot
                    'is_bot' => true,
                    'bot_name' => $botName,
                ]);
                
                return $next($request);
            }

            // Tentukan tipe device untuk pengunjung manusia
            $deviceType = 'desktop';
            if ($agent->isTablet()) {
                $deviceType = 'tablet';
            } elseif ($agent->isMobile()) {
                $deviceType = 'mobile';
            }

            // Deteksi lokasi (hanya untuk manusia)
            try {
                // Handle local development environment (Herd)
                if (in_array($request->ip(), ['127.0.0.1', '::1']) || app()->environment('local')) {
                    $country = 'Indonesia'; // Default country for local development
                } else {
                    $geoip = geoip($request->ip());
                    $country = $geoip['country'] ?? null;
                }
            } catch (\Exception $e) {
                $country = null;
                \Log::warning('GeoIP error: ' . $e->getMessage());
            }

            // Cek IP dan hindari duplikasi pengunjung yang sama dalam waktu dekat
            $recentVisit = Visitor::where('ip_address', $request->ip())
                ->where('created_at', '>=', now()->subMinutes(5))
                ->exists();
                
            // Jika bukan pengunjung yang sama (dalam 5 menit terakhir)
            if (!$recentVisit) {
                Visitor::create([
                    'ip_address' => $request->ip(),
                    'user_agent' => $userAgent,
                    'page_visited' => $request->fullUrl(),
                    'referer_url' => $request->headers->get('referer'),
                    'country' => $country,
                    'device_type' => $deviceType,
                    'is_bot' => false,
                    'bot_name' => null,
                ]);
            }
        }

        return $next($request);
    }
}
