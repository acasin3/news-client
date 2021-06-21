# Consume REST API Using PHP Curl

https://newsapi.org

## Pre-requisites

1. Enable curl. See [https://www.php.net/manual/en/curl.setup.php](https://www.php.net/manual/en/curl.setup.php)
2. Get an API key from [newsapi.org](https://newsapi.org/register/) and paste in the Php script placeholder. 

   _Before_
   ```bash
   $authorization = 'Authorization: Bearer <PASTE API KEY HERE>';
   $cookie = 'Cookie: Authorization=<PASTE API KEY HERE>';
   ```

   _After_
   ```bash
   $authorization = 'Authorization: Bearer *************************';
   $cookie = 'Cookie: Authorization=*************************';
   ```

## Next Steps
1. Sanitize query string parameters.
2. Standardize image heights.
3. Get data via ajax.
