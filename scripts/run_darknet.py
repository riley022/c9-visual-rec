import sys
import subprocess
from subprocess import call
import time
import cv2 
import datetime 
import json
import csv
import MySQLdb

def export_csv(data):
	with open('scripts/dataset_darknet.csv', 'a') as f:  # Just use 'w' mode in 3.x
		w = csv.DictWriter(f, data.keys())
		#w.writeheader()
		w.writerow(data)
		# writes data to a csv file called 'dataset_darknet', if there is no such a file, a new one will be created.
		name = str(image_location.replace('.jpg',""));
		sql = "INSERT INTO tag(product_ref, type, name, confidence, width, height) VALUES ("+name+", 'darknet', '"+predictedObject+"', '"+accuracy+"', '"+imgWidth+"', '"+imgHeight+"')"
    	print(sql);
    	cursor = db.cursor()
        cursor.execute(str(sql))
        db.commit()

# Database connection
db = MySQLdb.connect("localhost","rgre2543","rgre2543","visualrecognition" )

print(len(sys.argv[:]))
if len(sys.argv[:]) == 3:
	type = sys.argv[1];
	image_location = sys.argv[2];
	image_location2 = "https://visual-recognition-rgre2543.c9users.io/product-images/"+image_location;
	if (type != "darknet") and (type != "darknet19") and (type != "darknet19-large") and (type != "yolo") and (type != "yolo-better"):
		print("Please enter a correct service name, e.g. darknet, darknet19, darknet19-large, yolo, yolo-better!");
		exit();
else:
	print("Please enter a service type and a image location");
	print("Please enter a correct service name, e.g. darknet, darknet19, darknet19-large, yolo, yolo-better!");
	exit();


cmd1 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; wget -P /var/www/html/img/ " + image_location2 + " '";
print(cmd1);
output = subprocess.Popen(['/bin/bash', '-c', cmd1]);
time.sleep(5);

if (type == "darknet"):
	string = "./darknet classifier predict cfg/imagenet1k.data Imgnet/darknet/darknet.cfg Imgnet/darknet/darknet.weights ";
	cmd5 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; " + string + " /var/www/html/img/" + image_location + " '";
	output = subprocess.Popen(['/bin/bash', '-c', cmd5], stdout=subprocess.PIPE);
	output = output.communicate()[0];

if (type == "darknet19"):
	string = "./darknet classifier predict cfg/imagenet1k.data Imgnet/darknet19/darknet19.cfg Imgnet/darknet19/darknet19.weights ";
	cmd5 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; " + string + " /var/www/html/img/" + image_location + " '";
	output = subprocess.Popen(['/bin/bash', '-c', cmd5], stdout=subprocess.PIPE);
	output = output.communicate()[0];

if (type == "darknet19-large"):
	string = "./darknet classifier predict cfg/imagenet1k.data Imgnet/darknet19-large/darknet19_448.cfg Imgnet/darknet19-large/darknet19_448.weights ";
	cmd5 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; " + string + " /var/www/html/img/" + image_location + " '";
	output = subprocess.Popen(['/bin/bash', '-c', cmd5], stdout=subprocess.PIPE);
	output = output.communicate()[0];

if (type == "yolo"):
	string = "./darknet classifier predict cfg/imagenet1k.data Imgnet/yolo/yolo.cfg Imgnet/yolo/yolo.weights ";
	cmd5 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; " + string + " /var/www/html/img/" + image_location + " '";
	output = subprocess.Popen(['/bin/bash', '-c', cmd5], stdout=subprocess.PIPE);
	output = output.communicate()[0]; 

if (type == "yolo-better"):
	string = "./darknet detect Imgnet/yolo/yolo.cfg Imgnet/yolo/yolo.weights ";
	cmd5 = "ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/YOLO/darknet ; " + string + " /var/www/html/img/" + image_location + " '";
	output = subprocess.Popen(['/bin/bash', '-c', cmd5], stdout=subprocess.PIPE);
	output = output.communicate()[0];

# Get image dimensions and place in dictionary
image_path = "product-images/" + image_location;
img = cv2.imread(image_path);
(height, width, channels) = img.shape;
imgWidth = str(width);
imgHeight = str(height);

timeProcessed = str(datetime.datetime.now());

length = str(output).count('\n')
output2 = str(output).split('\n')
print(output2)

for i in range(0,length-1):
	#dog: 89%
	if type == "yolo-better" or type == "yolo":
		accuracy = str(round(int((output2[i+1].split('%')[0]).split(':')[1]),2));
		predictedObject = (output2[i+1].split('%')[0]).split(':')[0];
	
	#'42.96%: Brittany spaniel'
	if type == "darknet" or type == "darknet19" or type == "darknet19-large":
		accuracy = (output2[i+1].split('%: ')[0]).split('.')[0]
		predictedObject = (output2[i+1].split('%: ')[1]);
		
	objectTag = "/var/www/html/img/" + image_location;
	data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type};
	export_csv(data);
	