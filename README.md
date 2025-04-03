# nginx_tester

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


**ava.jpg**

```
GET /img/ava.jpg HTTP/1.1
Host: localhost
Connection: close
```

`POST`
---


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


