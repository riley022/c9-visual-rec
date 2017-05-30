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

def get_snapshot():
    file_name = sys.argv[1]

file_name = sys.argv[1]
# The full url of the image
cmd1 = "mv /home/ubuntu/workspace/product-images/" + file_name + " /home/ubuntu/workspace/product-videos/"
print(cmd1)
#output = subprocess.Popen(['/bin/bash', '-c', cmd1], stdout=subprocess.PIPE);

cmd2 = "ffmpeg -i /home/ubuntu/workspace/product-videos/" + file_name + " -ss 00:00:01:00 -vframes 1 /home/ubuntu/workspace/product-images/" + file_name[0:-4] + ".jpg"
print(cmd2)
#output = subprocess.Popen(['/bin/bash', '-c', cmd2], stdout=subprocess.PIPE);

