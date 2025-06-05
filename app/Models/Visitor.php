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
     * Hitung pengunjung unik berdasarkan rentang waktu tertentu
     */
    public static function getUniqueVisitorsCount($days = 7): int
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->distinct('ip_address')
            ->count('ip_address');
    }
    
    /**
     * Dapatkan total halaman yang dikunjungi
     */
    public static function getTotalPageViews($days = 7): int
    {
        return self::where('created_at', '>=', Carbon::now()->subDays($days))
            ->count();
    }
    
    /**
     * Dapatkan halaman yang paling banyak dikunjungi
     */
    public static function getMostVisitedPages($limit = 5): array
    {
        return self::select('page_visited', \DB::raw('count(*) as total'))
            ->groupBy('page_visited')
            ->orderBy('total', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
