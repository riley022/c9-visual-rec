'''
An image recognition analysis powered by Azure Computer Vision API.

Prequisite: An image needs to be uploaded to the server at
        http://52.65.25.198/img/{imageTitle}

Input: The imageTitle in command line arguments (including the
        file format). For example: 'banana.jpg'

Output: An updated csv file saved in the same directory of this code,
        named as dataset.csv, containing seven columns (timestamp,
        title of this image, identified tags, confidence rate of the
        tag, image width and image height).
'''

import httplib, urllib, base64
import sys
import csv
import json
import datetime
import MySQLdb
import scp
from subprocess import call

# Database connection
db = MySQLdb.connect("localhost","rgre2543","rgre2543","visualrecognition" )

# Read the title of the image
file_name = sys.argv[1]

# Grabs product primary key
primary_key = file_name.split(".")[0]

headers = {
    # Request headers
    'Content-Type': 'application/json',
    'Ocp-Apim-Subscription-Key': 'e19c82f5925945d8ae1909c05c386d4c',
}

params = urllib.urlencode({
    # Request parameters
    'visualFeatures': 'tags',
    'language': 'en',
})

# The full url of the image
cmd = "scp -i scripts/rsa-private-key.pem product-images/"+ file_name +" ubuntu@54.153.238.139:/var/www/html/img/"
call(cmd.split(" "))

body = "{'url':'http://54.153.238.139/img/" + file_name +"'}"

time = str(datetime.datetime.now())

def export_csv(data):
    data_json = json.loads(data)
    sys.stdout.write(data)
    sys.stdout.flush()
    f = csv.writer(open("scripts/dataset_azure.csv", "a"))
    
    # writes data to a csv file called 'dataset_azure', if there is no such a file, a new one will be created.
    for x in [data_json]:
        no_tags = len(x["tags"])
        for i in range(0, no_tags):
             f.writerow([time,
                    file_name,
                    x["tags"][i]["name"],
                    x["tags"][i]["confidence"],
                    x["metadata"]["width"],
                    x["metadata"]["height"]]
                    )
    cmd2 = "scp -i scripts/rsa-private-key.pem scripts/dataset_azure.csv ubuntu@54.153.238.139:/opt/data/"
    call(cmd2.split(" "))    
    
    # writes data to a MySQL table called 'tag'.
    for x in [data_json]:
        no_tags = len(x["tags"])
        for i in range(0, no_tags):
            name = str(x["tags"][i]["name"])
            confidence = str(round(float(x["tags"][i]["confidence"])*100, 2))
            width = str(x["metadata"]["width"])
            height = str(x["metadata"]["height"])
            sql = "INSERT INTO tag(product_ref, type, name, confidence, width, height) VALUES ("+primary_key+", 'azure-tags', '"+name+"', '"+confidence+"', '"+width+"', '"+height+"')"
            print(sql)
            cursor = db.cursor()
            cursor.execute(str(sql))
            db.commit()
    return

#body of the code starts from next line
try:
    conn = httplib.HTTPSConnection('westus.api.cognitive.microsoft.com')
    #v1.0/describe
    #conn.request("POST", "/vision/v1.0/describe?%s" % params, body, headers)
    #tags
    conn.request("POST", "/vision/v1.0/analyze?%s" % params, body, headers)
    response = conn.getresponse()
    data = response.read()
    export_csv(data)
    conn.close()
except Exception as e:
    print(e)