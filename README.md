# Consume REST API

For simple projects, there is no need to use a framework to consume REST API's.  For this project, the [top-headlines](https://newsapi.org/docs/endpoints/top-headlines) endpoint of the https://newsapi.org API will be used.

Initially, API calls will be made using CURL. The code will then be refactored to add a wrapper around CURL.

As file structure is not the focus of this repository, html and php files will all be in the root.

## Pre-requisites

1. Enable curl. See [https://www.php.net/manual/en/curl.setup.php](https://www.php.net/manual/en/curl.setup.php)
2. Get an API key from [newsapi.org](https://newsapi.org/register/) and paste in the placeholder of the **top-headlines.php** file. 

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

## Implementation

To see the various stages of the implementation, see the details of each commit in the [commit history](https://github.com/acasin3/news-client/commits/main/top-headlines.php) of the **top-headlines.php** file.

![image](https://user-images.githubusercontent.com/59311849/123371167-c8d1ff80-d5b3-11eb-9442-408dab3a8abc.png)

## Screeshot

![landing_page](https://user-images.githubusercontent.com/59311849/143609152-cc9315e6-dd5c-4068-9e76-a074cfd1e42f.PNG)

## Next Steps
1. Standardize image heights.
2. Get data via ajax.
