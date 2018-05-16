# PHP-PSNPublicTrophies

This is a simple PHP library that will get PSN profile and trophies by PSN ID.

**NOTE: Your PSN account must have 2-Step Verification enabled**

**Install via Composer**
```
composer require vhin0210/php-psnpublictrophies
```

# How to use
**Get Access Token**
```
$psnAuth = new \PSNPublicTrophiesLib\PSNPublicTrophiesAuth();

// Get Ticket UUID from Playstation Network by loging in to Playstation Network (works best with 2-Step Verification)
// Get the login URL. Copy the URL and open in a new tab
// Login and copy the whole URL. Your PSN account must have 2-Step Verification enabled, don't enter the Verification Code on that PS form.
$authUrl = $psnAuth->getAuthenticateUrl();

// Get Ticket UUID from the URL you copied.
$auth_url_parameters = $psnAuth->getAuthenticateUrlParameters($ticket_uuid_url);
$ticket_uuid = NULL;
if (isset($auth_url_parameters['ticket_uuid'])) {
  $ticket_uuid = $auth_url_parameters['ticket_uuid'];
}

// Authenticate with all the credentials
// $psn_id - your PSN ID
// $password - your password
// $ticket_uuid - the ticket uuid from the url you copied from playstation network
// $verification_code - code from the SMS you get from Playstation
$auth = $psnAuth->authenticate($psn_id, $password, $ticket_uuid, $verification_code);

// Get the access token (array). You can save this token in your database
$psn_account_token = $auth->GetTokens();
```

**Refresh Token**
```
// Initialize the library with the access token. This will automatically refresh the token.
// $psn_account_token - the access token (array)
$psnAuth = new \PSNPublicTrophiesLib\PSNPublicTrophiesAuth($psn_account_token);

// Get the new access token
$psn_account_token = $psnAuth->access_token;
```

**Get Profile**
```
$psnAuth = new \PSNPublicTrophiesLib\PSNPublicTrophiesAuth($psn_account_token);

$profileData = $psnAuth->getProfile();
```
