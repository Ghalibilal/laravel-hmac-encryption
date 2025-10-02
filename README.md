# 🔐 Laravel Encryption by Hazrat Bilal Ghalib

A simple and secure Laravel package for encrypting and decrypting model attributes, queries, and sensitive data using AES-256-CBC with HMAC integrity.

✅ Supports `.env`-based encryption keys and automatic attribute encryption via model casting.

---

## 📦 Features

- 🔐 AES-256-CBC encryption with HMAC SHA-256  
- 🧠 Automatic encryption/decryption via Eloquent attribute casting  
- 🧱 Custom query macros: `whereEncrypted`, `orWhereEncrypted`  
- ⚙️ Configurable encryption key and IV from `.env`  
- ✅ Facade support for easy usage  

---

## 🚀 Installation

```bash
composer require ghalibilal/laravel-hmac-encryption:dev-main
```

Then publish the configuration file:
```bash
php artisan vendor:publish --tag=config
```

🔐 .env Configuration

You must define two secure values in your .env file:
```bash
ENCRYPTION_KEY=base64:your_base64_encoded_32_byte_key_here
ENCRYPTION_SECRET=base64:your_base64_encoded_16_byte_iv_here
```

🛠 How to generate secure keys
```bash
Generate a 32-byte encryption key:

php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"


Generate a 16-byte IV (initialization vector):

php -r "echo 'base64:' . base64_encode(random_bytes(16)) . PHP_EOL;"
```


⚠️ These values are required and must be securely stored.

🧠 Usage of EncryptedCast in a Model

Automatically encrypt/decrypt specific model fields:
```bash

use Illuminate\Database\Eloquent\Model;
use Ghalibilal\LaravelEncryption\Casts\EncryptedCast;

class User extends Model
{
    protected $casts = [
        'email' => EncryptedCast::class,
        'phone' => EncryptedCast::class,
    ];
}
```


🔍 Query Using Encrypted Values
```bash
User::whereEncrypted('email', '=', 'secret@example.com')->first();
User::orWhereEncrypted('phone', '=', '1234567890')->get();
```

## 🔑 Artisan Commands

### Encrypt Model Attributes

You can encrypt attributes of a model directly from the terminal using:

```bash
php artisan encrypt:model --class="App\\Models\\User"
```



The package automatically encrypts the value before executing the query.

```bash
🧪 Example
use Ghalibilal\LaravelEncryption\Facades\Encryption;

$plain = 'Sensitive info';

$encrypted = Encryption::encrypt($plain);
$decrypted = Encryption::decrypt($encrypted);
```
