# Eltech Academy: one-time server setup

The academy needs three things that must NOT be in this public repo:
a database, a Google sign-in key, and a small settings file on the server.

## 1. Create the database (cPanel)

1. cPanel > **MySQL Databases**.
2. Create a database, for example `academy` (cPanel adds your account prefix, e.g. `abcd_academy`).
3. Create a user with a strong password.
4. Add the user to the database with **ALL PRIVILEGES**.

The academy creates its own tables the first time it runs.

## 2. Create the Google sign-in key

1. Go to https://console.cloud.google.com/ and create a project, e.g. "Eltech Academy".
2. **APIs & Services > OAuth consent screen**: choose *External*, app name "Eltech Academy",
   your support email, then **Publish app** (so anyone can sign in).
3. **APIs & Services > Credentials > Create credentials > OAuth client ID**:
   - Application type: **Web application**
   - Authorized JavaScript origins: `https://eltech-systems.com` and `https://www.eltech-systems.com`
4. Copy the **Client ID** (ends in `.apps.googleusercontent.com`). No client secret is needed.

## 3. Create the settings file on the server

In cPanel **File Manager**, open your home folder (the folder that CONTAINS `public_html`,
not public_html itself) and create `eltech-academy-config.php`:

```php
<?php
return [
    'db' => [
        'host' => 'localhost',
        'name' => 'abcd_academy',        // your database name
        'user' => 'abcd_academyuser',    // your database user
        'pass' => 'YOUR-DATABASE-PASSWORD',
    ],
    'google_client_id' => 'YOUR-CLIENT-ID.apps.googleusercontent.com',
    'admin_emails' => ['kavinyia@gmail.com'],   // who can open /academy/admin.php
    'notify_email' => 'kavinyia@gmail.com',     // where new students, tasks and payments are reported
    'dev_login' => false,
    'marzpay' => [                               // mobile money payments (optional)
        'api_key' => 'YOUR-MARZPAY-API-KEY',
        'api_secret' => 'YOUR-MARZPAY-API-SECRET',
        'webhook_secret' => '',                  // only if webhook signing is on in Marz Pay
    ],
];
```

Without the `marzpay` block, the course page falls back to "pay and tell us" (manual unlock).
In the Marz Pay dashboard, subscribe to the **Collections** service (MTN and Airtel Uganda).

Because it sits outside public_html, it can never be opened in a browser or uploaded to GitHub.

## 4. Test

Open https://eltech-systems.com/academy/, sign in with Google, then open
https://eltech-systems.com/academy/admin.php with an admin email.

## Adding a course later

1. Write `academy/content/<course-slug>.php` (copy `ai-beyond-chatgpt.php` as a template).
2. In `academy/lib/courses.php`, set that course's `'status' => 'live'`.
3. Set a price in the content file (`'price' => 'UGX 150,000'`) if you want it shown on the unlock box.
