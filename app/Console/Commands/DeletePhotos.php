<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhotoDetail;
use App\Models\Setting;
use App\Models\PhotoUploadTrack;
use App\Models\PhotoReport;
use App\Models\Notifications;
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
    // public function handle()
    // { 
    //     $settings = Setting::first();
    //     $days = $settings->delete_photos_after_days ?? 30;
    //     $photos = PhotoDetail::where('created_at', '<', now()->subDays($days))->get();
    //     foreach ($photos as $photo) {
    //         if (Storage::disk('public')->exists($photo->photo)) {
    //             Storage::disk('public')->delete($photo->photo);
    //             $this->info("Deleted file: {$photo->photo}");
    //         }
    //         if ($photo->uploadTrack) {
    //             $photo->uploadTrack()->delete();
    //             $this->info("Deleted related uploadTrack for photo ID: {$photo->id}");
    //         }
    //         if ($photo->views()->exists()) {
    //             $photo->views()->delete();
    //             $this->info("Deleted related views for photo ID: {$photo->id}");
    //         }
    //         $photo->delete();
    //         $this->info("Deleted DB record for photo ID: {$photo->id}");
    //     }
    //     $this->info("Photos older than {$days} days deleted successfully!");
    // }



    // Soft Delete the photo and its available data from the all table after specific days set by the admin in the settings // 
    public function handle()
    { 
       $settings = Setting::first();
        $days = $settings->delete_photos_after_days ?? 30;

        $photos = PhotoDetail::where('created_at', '<', now()->subDays($days))
                    ->where('state', '!=', -1)
                    ->get();

        foreach ($photos as $photo) {

            // Update upload tracks state
            if ($photo->uploadTrack) {
                $photo->uploadTrack()->update([
                    'state' => -1
                ]);

                $this->info("Updated uploadTrack state for photo ID: {$photo->random_id }");
            }

            // Update views state
            if ($photo->views()->exists()) {
                $photo->views()->update([
                    'state' => -1
                ]);

                $this->info("Updated views state for photo ID: {$photo->random_id }");
            }

            // Update photo_reports
            PhotoReport::where('photo_random_id', $photo->random_id )
                ->update(['state' => -1]);

            $this->info("Updated PhotoReport state for photo ID: {$photo->random_id }");

            // Update notifications
            Notification::where('photo_random_id', $photo->random_id )
                ->update(['state' => -1]);

            $this->info("Updated Notification state for photo ID: {$photo->random_id }");

            // Update photo_details
            $photo->update([
                'state' => -1
            ]);

            $this->info("Updated photo_details state for photo ID: {$photo->random_id }");
        }

        $this->info("Photos older than {$days} days updated successfully!");
    }
}
