# nginx_tester

## How to mannualy test

```bash
telnet 127.0.0.1 8090
```

## Testers to implement

- [ ] Parser tester
  - test grammar
  - test methods GET/POST/DELETE
- [ ] Resilience tester
  - test resilience to many request, good or bad
- [ ] Config tester
  - test different configs
  - each configs is tested with parser, resilience tester
  - specific test for each config to see if it reacts correctly
- [ ] [OPTIONAL] Response tester (test if responses are valid)
- [ ] [OPTIONAL] CGI tester
- [ ] [BONUS] Cookies & session management tester

## Valid requests

`GET`
---

**index.html**

```
GET /index.html HTTP/1.1
Host: localhost
Connection: close
```

**index.html: host instead of Host**

```
GET /index.html HTTP/1.1
host: localhost
Connection: close
```

**index.html: host with a random name**

```
GET /index.html HTTP/1.1
Host: nimp
Connection: close
```

**index.html: no space for a the Host field**

```
GET /index.html HTTP/1.1
Host:localhost
Connection: close
```


**index.html: multiple hosts**

```
GET /index.html HTTP/1.1 
Host: localhost google.com
Connection: close
```

**/dontexist.html (when the config allows it)**

```
GET /dontexist.html HTTP/1.1
Host: localhost
Connection: close
```

**index.html: unkwown header field**

```
GET /index.html HTTP/1.1
Host: localhost
nimporte: nimporte
Connection: close
```

**index.html: host with ip address**

```
GET / HTTP/1.1
Host: 127.0.0.1
Connection: close
```

or

```
GET / HTTP/1.1
Host: 0.0.0.0
Connection: close
```

**Multiple request line**

```
GET / HTTP/1.1
GET / HTTP/1.1
Host: localhost
Connection: close
```

or

```
GET / HTTP/1.1
GET /img/ava.jpg HTTP/1.1
Host: localhost
Connection: close
```

or 

```
GET /img/ava.jpg HTTP/1.1
GET / HTTP/1.1
Host: localhost
Connection: close
```

The first resources is taken into account, the other is ignored

**ava.jpg**

```
GET /img/ava.jpg HTTP/1.1
Host: localhost
Connection: close
```

`POST`
---

**Simple request**

```
POST /index.php HTTP/1.1
Host: localhost
Content-Type: application/x-www-form-urlencoded
Content-Length: 13

foo=bar&baz=1
```

This requires nginx configure with php cgi and an index.php to work

**Create a file**

```
POST /new.php?filename=lol HTTP/1.1
Host: localhost
Content-Type: text/plain
Content-Length: 11

hello world
```


`DELETE`
---

## Invalid / Bad requests

`Miscellanous`
---

**no request line**

```
Host: localhost
Connection: close
```

*Response*
```
HTTP/1.1 400 Bad Request
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 06:59:24 GMT
Content-Type: text/html
Content-Length: 157
Connection: close

<html>
<head><title>400 Bad Request</title></head>
<body>
<center><h1>400 Bad Request</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```
Nginx respond `400: Bad Request` and send an html file with describing the error.

**request with URI + HTTP version only**

```
/index.html HTTP/1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.

**unknown method looking like GET**

```
nimportequoi /index.html HTTP/1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file describing the error.

**unknown method only**

```
obtenir
```

*Response*

Nginx respond `400: Bad Request` and send an html file describing the error.

**URI only**

```
/index.html
```

*Response*

Nginx respond `400: Bad Request` and send an html file describing the error.

**HTTP version only**

```
HTTP /1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file describing the error.


**unkown method on caps**

```
OBTENIR /index.html HTTP/1.1
```

*Response*

```
HTTP/1.1 405 Not Allowed
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 10:49:59 GMT
Content-Type: text/html
Content-Length: 157
Connection: close

<html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```


Nginx respond `405: Not Allowed` and send an html file describing the error.


`GET`
---

**index.html: no HTTP version**

```
GET /index.html
Host: localhost
Connection: close
```

*Response*
Nginx delivers the html file without header, only the body is sent. As soon as it read `Host` header line it seems.

**index.html: only GET**

```
GET
Host: localhost
Connection: close
```

*Response*
Nginx delivers the html file without header, only the body is sent. As soon as it read `Host` header line it seems.


**custom.css: no HTTP version**

```
GET /css/custom.css
Host: localhost
Connection: close
```

*Response*
Nginx delivers the html file without header, only the body is sent. As soon as it read `Host` header line it seems.

**index.html: no Host header field**

```
GET /index.html HTTP/1.1
```

or

```
GET /index.html HTTP/1.1
Connection: close
```

*Response*

```
HTTP/1.1 400 Bad Request
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 07:20:45 GMT
Content-Type: text/html
Content-Length: 157
Connection: close

<html>
<head><title>400 Bad Request</title></head>
<body>
<center><h1>400 Bad Request</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```

Nginx respond `400: Bad Request` and send an html file with describing the error.


**index.html: typo on HTTP**

```
GET /index.html HTPP/1.1
Host: localhost
Connection: close
```

*Response*

```
HTTP/1.1 400 Bad Request
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 07:28:37 GMT
Content-Type: text/html
Content-Length: 157
Connection: close

<html>
<head><title>400 Bad Request</title></head>
<body>
<center><h1>400 Bad Request</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```

Nginx respond `400: Bad Request` and send an html file with describing the error. **It seems to be as soon as it detects the parsing error**.


**index.html: http instead of HTTP**

```
GET /index.html http/1.1 
```

*Response*

Nginx delivers the html file without header, only the body is sent. **It seems it doesn't want to deal with further demand from the client, the connection is closed right after processing the request line**.

**index.html: Host field empty**

```
GET /index.html HTTP/1.1
Host:
```

or

```
GET /index.html HTTP/1.1
Host:<multiple spaces>
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error. **It seems to be as soon as it detects the parsing error**.

**index.html: field with spaces before colons ':'**

```
GET /index.html HTTP/1.1
Host      : localhost
Connection: close
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.

**index.html: Connection close with space before ':'**

```
GET /index.html HTTP/1.1
Host: localhost
Connection      : close
```

*Response*

Nginx respond `200: OK` but does not close the connection, as if the field `Connection` was ignored due to parsing error.


**index.html: HTTP version not handled**

```
GET / HTTP/6.1
```

*Response*

```
HTTP/1.1 505 HTTP Version Not Supported
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 09:38:33 GMT
Content-Type: text/html
Content-Length: 187
Connection: close

<html>
<head><title>505 HTTP Version Not Supported</title></head>
<body>
<center><h1>505 HTTP Version Not Supported</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```

Nginx respond `505: HTTP Version Not Supported` and send a html file describing the error.

**index.html: only HTTP**

```
GET /index.html HTTP
```

or

```
GET / HTTP/
```

*Response*

```
HTTP/1.1 400 Bad Request
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 09:42:35 GMT
Content-Type: text/html
Content-Length: 157
Connection: close
```

Nginx respond `400: Bad Request` and send an html file with describing the error.


**index.html: /1.1 only**

```
GET / /1.1
```

*Response*

Nginx delivers the html file without header, only the body is sent. **It seems it doesn't want to deal with further demand from the client, the connection is closed right after processing the request line**.

**URI = ??? (no /)**

```
GET ??? HTTP/1.1
```

or

```
GET saluttoi HTTP/1.1
```

*Response*
Nginx respond `400: Bad Request` and send an html file with describing the error.

**URI = random_name.html (there is no /)**

```
GET pleure.html HTTP/1.1
```

*Response*
Nginx respond `400: Bad Request` and send an html file with describing the error.

**URI = /dontexist.html (nginx configured to throw 404)**

```
GET /dontexist.html HTTP/1.1
Host: localhost
Connection: close
```

*Response*

```
HTTP/1.1 404 Not Found
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 10:18:26 GMT
Content-Type: text/html
Content-Length: 153
Connection: close

<html>
<head><title>404 Not Found</title></head>
<body>
<center><h1>404 Not Found</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```

Nginx respond with the famous `404: Not Found` and send an html file describing the error.

**index.html: additional token at the end of request line**

```
GET / HTTP/1.1 yohou 
```

*Response*

```
HTTP/1.1 400 Bad Request
Server: nginx/1.18.0
Date: Thu, 03 Apr 2025 10:24:13 GMT
Content-Type: text/html
Content-Length: 157
Connection: close

<html>
<head><title>400 Bad Request</title></head>
<body>
<center><h1>400 Bad Request</h1></center>
<hr><center>nginx/1.18.0</center>
</body>
</html>
```

Nginx respond `400: Bad Request` and send an html file with describing the error.

**index.html: get instead of GET**

```
get /index.html HTTP/1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.


**index.html: GeT instead of GET**

```
GeT /index.html HTTP/1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.


**index.html: HTtP instead of HTTP**

```
GET /index.html HTtP/1.1
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.

POST
---

**No double CRLF between last header field and mesage body**

```
POST /index.php HTTP/1.1
Host: localhost
Content-Type: application/x-www-form-urlencoded
Content-Length: 13
foo=bar&baz=1
```

*Response*

No response

**POST without HTTP version**

```
POST /index.php
Host: localhost
Content-Type: application/x-www-form-urlencoded
Content-Length: 13

foo=bar&baz=1
```

*Response*

Nginx respond `400: Bad Request` and send an html file with describing the error.

**POST on index.html**


```
POST /index.html HTTP/1.1
Host: localhost
Content-Type: application/x-www-form-urlencoded
Content-Length: 13

foo=bar&baz=1
```

*Response*

```
HTTP/1.1 405 Not Allowed
Server: nginx/1.27.4
Date: Sun, 06 Apr 2025 13:13:07 GMT
Content-Type: text/html
Content-Length: 157
Connection: keep-alive

<html>
<head><title>405 Not Allowed</title></head>
<body>
<center><h1>405 Not Allowed</h1></center>
<hr><center>nginx/1.27.4</center>
</body>
</html>
```


Nginx respond `405: Not Allowed` and send an html file with describing the error.

**POST with an non existing URI**

```
POST /dontexist HTTP/1.1
Host: localhost
Content-Type: application/x-www-form-urlencoded
Content-Length: 13

foo=bar&baz=1
```

*Response*

Nginx respond `404: Not Found` and send an html file with describing the error.


DELETE
---

