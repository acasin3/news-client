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
After
$authorization = 'Authorization: Bearer 1a2b3c4de5f6...';
$cookie = 'Cookie: Authorization=1a2b3c4de5f6...';
```
