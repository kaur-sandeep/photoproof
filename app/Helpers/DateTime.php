<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DateTime
{
    
    public static function dateFormat($date)
    {
        return $date
            ? Carbon::parse($date)
                ->setTimezone('Asia/Kolkata')
                ->format('M d Y h:i:s A T')
            : '-';
    }
}
