import socket

# This bytes literal contains a NUL (\x00) followed by “ABC” and then \r\n\r\n
req = b"GET / HTTP/1.1\r\nHost:ABC\r\n\Acc(ept: errerloleoerer\r\n\r\n"

s = socket.create_connection(("localhost", 8090))
s.sendall(req)
print(s.recv(1024))
s.close()
