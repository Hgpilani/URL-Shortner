<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateShortUrlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urls:generate
                            {original_url : The full destination URL}
                            {company_id : Company that owns the URL}
                            {created_by : User ID used as creator}
                            {--code= : Optional custom short code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate short URL as a system action';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $originalUrl = (string) $this->argument('original_url');
        $companyId = (int) $this->argument('company_id');
        $createdBy = (int) $this->argument('created_by');
        $manualCode = $this->option('code');

        if (! filter_var($originalUrl, FILTER_VALIDATE_URL)) {
            $this->error('original_url must be a valid URL.');

            return self::FAILURE;
        }

        $companyExists = DB::table('companies')->where('id', $companyId)->exists();
        if (! $companyExists) {
            $this->error('Company not found.');

            return self::FAILURE;
        }

        $creator = DB::table('users')->where('id', $createdBy)->first();
        if (! $creator) {
            $this->error('Creator user not found.');

            return self::FAILURE;
        }

        if ((int) $creator->company_id !== $companyId) {
            $this->error('Creator user must belong to the selected company.');

            return self::FAILURE;
        }

        try {
            $code = is_string($manualCode) && $manualCode !== ''
                ? trim($manualCode)
                : $this->generateUniqueCode();
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $exists = Url::query()->where('short_code', $code)->exists();
        if ($exists) {
            $this->error('Short code already exists. Use another code or remove --code.');

            return self::FAILURE;
        }

        $url = Url::query()->create([
            'company_id' => $companyId,
            'created_by' => $createdBy,
            'original_url' => $originalUrl,
            'short_code' => $code,
        ]);

        $this->info('Short URL generated successfully.');
        $this->line('ID: '.$url->id);
        $this->line('Code: '.$url->short_code);
        $this->line('Resolve path: /s/'.$url->short_code);

        return self::SUCCESS;
    }

    private function generateUniqueCode(): string
    {
        $length = (int) config('shortener.code_length', 8);
        $attempts = (int) config('shortener.max_generate_attempts', 10);

        for ($i = 0; $i < $attempts; $i++) {
            $code = Str::lower(Str::random($length));
            $exists = Url::query()->where('short_code', $code)->exists();

            if (! $exists) {
                return $code;
            }
        }

        throw new \RuntimeException('Unable to generate unique short code after several attempts.');
    }
}
