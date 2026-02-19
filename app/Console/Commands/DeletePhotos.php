<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhotoDetail;
use App\Models\Setting;
use App\Models\PhotoUploadTrack;
use Illuminate\Support\Facades\Storage;

class DeletePhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:delete-photos';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete photos older than 30 days';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     $photos = PhotoDetail::where('created_at', '<', now()->subDays(30))->get();
    //     foreach ($photos as $photo) {
    //         if (Storage::disk('public')->exists($photo->photo)) {
    //             Storage::disk('public')->delete($photo->photo);
    //             $this->info("Deleted file: {$photo->photo}");
    //         }
    //         $photo->delete();
    //         $this->info("Deleted DB record for photo ID: {$photo->id}");
    //     }
    //     $this->info('Photos deleted successfully!');
    // }

    // Delete the photo and its available data from the all table after specific days set by the admin in the settings // 
    public function handle()
    {
        // Get settings Data //
        $settings = Setting::first();
        $days = $settings->delete_photos_after_days ?? 30; // default 30 if not set
        $photos = PhotoDetail::where('created_at', '<', now()->subDays($days))->get();
        //   $minutes = 2;
        // $photos = PhotoDetail::where('created_at', '<', now()->subDays($minutes))->get();
        foreach ($photos as $photo) {
            if (Storage::disk('public')->exists($photo->photo)) {
                Storage::disk('public')->delete($photo->photo);
                $this->info("Deleted file: {$photo->photo}");
            }
            if ($photo->uploadTrack) {
                $photo->uploadTrack()->delete();
                $this->info("Deleted related uploadTrack for photo ID: {$photo->id}");
            }
            if ($photo->views()->exists()) {
                $photo->views()->delete();
                $this->info("Deleted related views for photo ID: {$photo->id}");
            }
            $photo->delete();
            $this->info("Deleted DB record for photo ID: {$photo->id}");
        }
        $this->info("Photos older than {$days} days deleted successfully!");
    }
}
