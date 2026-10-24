import urllib.request
import json
try:
    req = urllib.request.Request("http://localhost:8080/index.php/api/wilayah/caridesa?q=Bumi%20Daya")
    with urllib.request.urlopen(req) as response:
        print(response.read().decode())
except Exception as e:
    print(e)
