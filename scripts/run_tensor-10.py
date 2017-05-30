'''
An image recognition analysis powered by TensorFlow.

Prequisite: An image needs to be uploaded to the server at
        http://52.65.25.198/img/{imageTitle}

Input: The imageTitle in command line arguments (including the
        file format). For example: 'banana.jpg'

Output: An updated csv file saved in the same directory of this code,
        named as dataset.csv, containing seven columns (timestamp,
        title of this image, identified tags, confidence rate of the
        tag, image width and image height).
'''
import sys
import csv
import json
import datetime
import MySQLdb
import scp
import subprocess
from subprocess import call
import time
import MySQLdb
import cv2

db = MySQLdb.connect("localhost","rgre2543","rgre2543","visualrecognition" )

def image_info():
    # Get image dimensions and place in dictionary
    image_path = sys.argv[1];
    img = cv2.imread("product-images/"+image_path);
    (height, width, channels) = img.shape;
    imgWidth = str(width);
    imgHeight = str(height);
    timeProcessed = str(datetime.datetime.now());
    objectTag = sys.argv[1];
    type1 = "tensor-10";
    return type1,objectTag,timeProcessed,imgHeight,imgWidth

def database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight):
        sql = "INSERT INTO tag(product_ref, type, name, confidence, width, height) VALUES ("+name+",'"+type1+"' , '"+predictedObject+"', '"+accuracy+"', '"+imgWidth+"', '"+imgHeight+"')"
    	print(sql);
    	cursor = db.cursor()
        cursor.execute(str(sql))
        db.commit()

def export_csv(data):
	with open('scripts/dataset_tensor-10.csv', 'a') as f:  # Just use 'w' mode in 3.x
		w = csv.DictWriter(f, data.keys())
		#w.writeheader()
		w.writerow(data)


# Database connection
# Read the title of the image
file_name = sys.argv[1]


cmd1 = "scp -i scripts/rsa-private-key.pem product-images/"+ file_name +" ubuntu@54.153.238.139:/var/www/html/img/"
call(cmd1.split(" "))
time.sleep(5)

# The full url of the image
cmd2 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/img_recognition; python label_image.py /var/www/html/img/" + file_name+ ";cd /opt/img_recognition; cp dataset_tensor-10.csv /var/www/html/csv/dataset_tensor-10.csv ;exit'"
print(cmd2)
output = subprocess.Popen(['/bin/bash', '-c', cmd2], stdout=subprocess.PIPE);
output2 = output.communicate()[0].split("\n");
print(output2)

time.sleep(12)
print('times up')

cmd4 = "rm scripts/dataset_tensor-10.csv"
print(cmd4)
subprocess.Popen(['/bin/bash', '-c', cmd4])

cmd5 = "wget -P scripts/ 54.153.238.139/csv/dataset_tensor-10.csv"
print(cmd5)
subprocess.Popen(['/bin/bash', '-c', cmd5])
time.sleep(5)

type1,objectTag,timeProcessed,imgHeight,imgWidth = image_info();

accuracy = output2[1].replace("Accuracy: ","")
predictedObject = output2[0].replace("Predicted Object: ","")
data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
print(data)
name = objectTag.replace(".jpg","");
database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight);
export_csv(data);

cmd6 = "exit"
print(cmd6)
subprocess.Popen(['/bin/bash', '-c', cmd6])

