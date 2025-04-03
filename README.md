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

`Other`
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