<?php
namespace App\Helpers;

use Carbon\Carbon;

class DateTime
{
    
    public static function dateFormat($date, $format = 'M d Y h:i:s A T')
    {
        if (!$date) {
            return '-';
        }

        // Database timestamps are stored as UTC without a timezone suffix.
        // Reparse Carbon instances as UTC too, since their timezone follows app.php.
        $value = $date instanceof \DateTimeInterface
            ? $date->format('M d Y h:i:s A T')
            : $date;

        return Carbon::parse($value, 'UTC')
            ->setTimezone('Asia/Kolkata')
            ->format($format);
    }
}
