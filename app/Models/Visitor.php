<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'page_visited',
        'referer_url',
        'country',
        'device_type',
        'is_bot',
        'bot_name',
    ];

    /**
     * Periksa apakah pengunjung dari mobile
     */
    public static function isMobile(string $userAgent): bool
    {
        return preg_match('/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|plucker|pocket|psp|symbian|smartphone|sony|treo|up\.browser|up\.link|webos|wos)/i', $userAgent);
    }
    
    /**
     * Periksa apakah pengunjung dari tablet
     */
    public static function isTablet(string $userAgent): bool
    {
        return preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $userAgent);
    }
    
    /**
     * Periksa apakah pengunjung adalah bot atau crawler
     * Dan kembalikan nama bot jika terdeteksi
     */
    public static function detectBot(string $userAgent): array
    {
        $botPatterns = [
            'Googlebot' => 'googlebot',
            'Google Ads Bot' => 'adsbot-google',
            'Bingbot' => 'bingbot',
            'Baiduspider' => 'baiduspider',
            'YandexBot' => 'yandexbot',
            'DuckDuckBot' => 'duckduckbot',
            'Yahoo Bot' => 'yahoo! slurp',
            'Facebook' => 'facebookexternalhit',
            'Twitter' => 'twitterbot',
            'LinkedIn' => 'linkedinbot',
            'WhatsApp' => 'whatsapp',
            'Telegram' => 'telegram',
            'Discord' => 'discordbot',
            'Pinterest' => 'pinterestbot',
            'Ahrefs' => 'ahrefsbot',
            'Semrush' => 'semrushbot',
            'Majestic' => 'majestic',
            'MJ12Bot' => 'mj12bot',
            'ScreamingFrog' => 'screaming frog',
            'Python' => 'python-requests',
            'Curl' => 'curl/',
            'Wget' => 'wget/',
            'Scraper' => 'scraper',
            'Crawler' => 'crawler',
            'Spider' => 'spider',
            'Apache' => 'apache-http',
            'Exploit Scanner' => 'nmap|nikto|sqlmap|vulnerscan|acunetix',
            'Generic Bot' => 'bot|crawl|slurp|spider|mediapartners',
        ];
        
        $lowerUserAgent = strtolower($userAgent);
        foreach ($botPatterns as $name => $pattern) {
            if (preg_match('/' . $pattern . '/i', $lowerUserAgent)) {
                return [
                    'is_bot' => true,
                    'bot_name' => $name
                ];
            }
        }
        
        // Deteksi malicious requests (potential security exploits)
        $maliciousPatterns = [
            'shell_exec', 'eval\(', 'base64_decode', '<?php',
            'system\(', 'passthru', 'exec\(', '<script',
            'union\s+select', 'concat\(', 'information_schema'
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $userAgent) || 
                preg_match('/' . $pattern . '/i', urldecode($userAgent))) {
                return [
                    'is_bot' => true,
                    'bot_name' => 'Malicious Bot'
                ];
            }
        }
        
        return [
            'is_bot' => false,
            'bot_name' => null
        ];
    }

    /**
     * Scope untuk query hanya pengunjung manusia
     */
    public function scopeHumansOnly($query)
    {
        return $query->where('is_bot', false);
    }
    
    /**
     * Scope untuk query hanya pengunjung bot
     */
    public function scopeBotsOnly($query)
    {
        return $query->where('is_bot', true);
    }

    /**
     * Hitung pengunjung unik berdasarkan rentang waktu tertentu
     */
    public static function getUniqueVisitorsCount($days = 7): int
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->humansOnly()
            ->distinct('ip_address')
            ->count('ip_address');
    }
    
    /**
     * Dapatkan total halaman yang dikunjungi
     */
    public static function getTotalPageViews($days = 7): int
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->humansOnly()
            ->count();
    }
    
    /**
     * Dapatkan halaman yang paling banyak dikunjungi
     */
    public static function getMostVisitedPages($limit = 5): array
    {
        return self::select('page_visited', \DB::raw('count(*) as total'))
            ->humansOnly()
            ->groupBy('page_visited')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
    
    /**
     * Hitung bots berdasarkan rentang waktu
     */
    public static function getBotsCount($days = 7): int
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->botsOnly()
            ->count();
    }
    
    /**
     * Dapatkan statistik bot per jenis
     */
    public static function getBotStatistics($days = 7, $limit = 10): array
    {
        return self::select('bot_name', \DB::raw('count(*) as total'))
            ->botsOnly()
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->whereNotNull('bot_name')
            ->groupBy('bot_name')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
