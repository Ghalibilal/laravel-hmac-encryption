<?php

namespace Ghalibilal\LaravelHmacEncryption\Console;
use Ghalibilal\LaravelHmacEncryption\Facades\Encryption;

use Illuminate\Console\Command;

class EncryptionCommand extends Command
{
    protected $signature = 'encrypt:model
                            {--class= : The full class name of the model (e.g. App\\Models\\User)}';

    protected $description = 'Auto-apply EncryptedCast to common PII fields in a given model';

    public function handle()
    {
        $modelClass = $this->option('class');

        if (!$modelClass || !class_exists($modelClass)) {
            $this->error('Invalid or missing --class argument.');
            return 1;
        }

        $modelInstance = new $modelClass;

        if (!method_exists($modelInstance, 'getCasts')) {
            $this->error("The class {$modelClass} does not appear to be a valid Eloquent model.");
            return 1;
        }

        $encryptedFields = collect($modelInstance->getCasts())
            ->filter(fn($cast) => $cast === \Ghalibilal\LaravelHmacEncryption\Casts\EncryptedCast::class)
            ->keys()
            ->toArray();

        if (empty($encryptedFields)) {
            $this->warn("No fields using EncryptedCast found in {$modelClass}.");
            return 0;
        }

        $this->info("Encrypting fields: " . implode(', ', $encryptedFields));

        $records = $modelClass::get();
        $count = 0;

        foreach ($records as $record) {
            $updated = false;

            foreach ($encryptedFields as $field) {
                $value = $record->{$field};

                if ($value) {
                    $record->{$field} = Encryption::encrypt($value);
                    $updated = true;
                }
            }

            if ($updated) {
                $record->save();
                $count++;
            }
        }

        $this->info("Encryption complete. {$count} record(s) updated.");
        return 0;
    }
}
