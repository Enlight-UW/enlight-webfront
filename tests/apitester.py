import urllib
import urllib2
import time
import json

i = 1
while True:
	i = i << 1
	if i > (1 << 24):
		i = 1
		
	data = {
			'apikey': 'abc123',
			'bitmask': i
	}

	req = urllib2.Request('http://alexkersten.com/enlight/api/valves')
	req.add_header('Content-Type', 'application/json')
	response = urllib2.urlopen(req, json.dumps(data))
	print response

	time.sleep(1)