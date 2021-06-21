# Consume REST API Using PHP Curl Demo

https://newsapi.org

## API Key

Get one from [newsapi.org](https://newsapi.org/register/). Then, search and replace the `<PASTE API KEY HERE>` placeholder in the **top-headlines.php** file. 

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
