<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateRopDatasetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dataset:generate-rop {days=365}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate dummy dataset for ROP prediction (Sales, Stock, and Event data)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->argument('days');
        $this->info("Generating dummy dataset for the last {$days} days...");

        $cabangs = DB::table('cabangs')->where('id', '!=', 1)->get(); // Skip dapur pusat
        $bahanBakus = DB::table('bahan_bakus')->get();

        if ($cabangs->isEmpty() || $bahanBakus->isEmpty()) {
            $this->error('Database is empty! Run php artisan db:seed first.');
            return;
        }

        $filename = storage_path('app/rop_dataset.csv');
        $file = fopen($filename, 'w');

        // CSV Header
        fputcsv($file, [
            'tanggal',
            'cabang_id',
            'nama_cabang',
            'nama_bahan',
            'kategori_bahan',
            'sisa_stok_harian',
            'jumlah_terjual_harian',
            'event_payday',
            'event_weekend',
            'event_holiday'
        ]);

        $bar = $this->output->createProgressBar($days * count($cabangs) * count($bahanBakus));
        $bar->start();

        // Simulate daily data from $days ago to today
        $startDate = Carbon::now()->subDays($days);

        // Keep track of stock for each branch & material
        $currentStocks = [];
        foreach ($cabangs as $cabang) {
            foreach ($bahanBakus as $bahan) {
                // Initial stock slightly randomized
                $currentStocks[$cabang->id][$bahan->id] = rand(50, 200);
            }
        }

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateStr = $date->format('Y-m-d');
            
            // Events
            $isWeekend = $date->isWeekend() ? 1 : 0;
            $isPayday = ($date->day >= 25 || $date->day <= 5) ? 1 : 0;
            $isHoliday = (in_array($date->month, [6, 7, 12])) ? 1 : 0;

            foreach ($cabangs as $cabang) {
                // Base daily donut sales for this branch
                $baseSales = rand(50, 150);
                if ($isWeekend) $baseSales += rand(50, 100);
                if ($isPayday) $baseSales += rand(30, 80);
                if ($isHoliday) $baseSales += rand(20, 60);

                foreach ($bahanBakus as $bahan) {
                    // Logic: How much material is used based on donut sales?
                    // Premix uses more, toppings use less. We simulate usage simply.
                    $usageMultiplier = 1;
                    if ($bahan->kategori === 'Bahan Pokok & Lemak') {
                        $usageMultiplier = 0.05; // 0.05 kg per donut
                    } elseif ($bahan->kategori === 'Kemasan') {
                        $usageMultiplier = 1; // 1 box
                    } elseif ($bahan->kategori === 'Glaze') {
                        $usageMultiplier = 0.01; // 0.01 pail
                    } elseif ($bahan->kategori === 'Topping') {
                        $usageMultiplier = 0.02; // 0.02 pack/kg
                    } else {
                        $usageMultiplier = 0.001;
                    }

                    // Random fluctuation per material
                    $usage = round($baseSales * $usageMultiplier * rand(8, 12) / 10, 2);
                    if ($usage < 0) $usage = 0;

                    // Update stock
                    $currentStocks[$cabang->id][$bahan->id] -= $usage;

                    // Restock if too low (below 20)
                    if ($currentStocks[$cabang->id][$bahan->id] < 20) {
                        $currentStocks[$cabang->id][$bahan->id] += rand(50, 150);
                    }

                    $stock = round($currentStocks[$cabang->id][$bahan->id], 2);

                    fputcsv($file, [
                        $dateStr,
                        $cabang->id,
                        $cabang->nama_cabang,
                        $bahan->nama_bahan,
                        $bahan->kategori,
                        $stock,
                        $usage, // using 'usage' as 'jumlah_terjual_harian' for this material
                        $isPayday,
                        $isWeekend,
                        $isHoliday
                    ]);
                    
                    $bar->advance();
                }
            }
        }

        $bar->finish();
        fclose($file);

        $this->newLine();
        $this->info("Dataset successfully generated at: {$filename}");
    }
}
