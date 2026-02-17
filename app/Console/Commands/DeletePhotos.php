<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhotoDetail;
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
    public function handle()
    {
        $photos = PhotoDetail::where('created_at', '<', now()->subDays(30))->get();
        foreach ($photos as $photo) {
            if (Storage::disk('public')->exists($photo->photo)) {
                Storage::disk('public')->delete($photo->photo);
                $this->info("Deleted file: {$photo->photo}");
            }
            $photo->delete();
            $this->info("Deleted DB record for photo ID: {$photo->id}");
        }
        $this->info('Photos deleted successfully!');
    }
}
