#!/usr/bin/env python

# Copyright 2017 Google Inc. All Rights Reserved.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

"""This application demonstrates how to perform basic operations with the
Google Cloud Vision API.

Example Usage:
python detect.py text ./resources/wakeupcat.jpg
python detect.py labels ./resources/landmark.jpg
python detect.py web ./resources/landmark.jpg
python detect.py web-uri http://wheresgus.com/dog.JPG
python detect.py faces-uri gs://your-bucket/file.jpg

For more information, the documentation at
https://cloud.google.com/vision/docs.
"""

import argparse
import io
from google.cloud import vision
import sys
import cv2
import datetime
import MySQLdb
import csv

db = MySQLdb.connect("localhost","rgre2543","rgre2543","visualrecognition" )

def export_csv(data):
	with open('scripts/dataset_google.csv', 'a') as f:  # Just use 'w' mode in 3.x
		w = csv.DictWriter(f, data.keys())
		#w.writeheader()
		w.writerow(data)
		
def image_info():
    # Get image dimensions and place in dictionary
    image_path = sys.argv[2];
    img = cv2.imread(image_path);
    (height, width, channels) = img.shape;
    imgWidth = str(width);
    imgHeight = str(height);
    timeProcessed = str(datetime.datetime.now());
    objectTag = sys.argv[2];
    type1 = "google-" + sys.argv[1];
    return type1,objectTag,timeProcessed,imgHeight,imgWidth
    
def database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight):
        sql = "INSERT INTO tag(product_ref, type, name, confidence, width, height) VALUES ("+name+",'"+type1+"' , '"+predictedObject+"', '"+accuracy+"', '"+imgWidth+"', '"+imgHeight+"')"
    	print(sql);
    	cursor = db.cursor()
        cursor.execute(str(sql))
        db.commit()

def detect_faces(path):
    type1,objectTag,timeProcessed,imgHeight,imgWidth = image_info();
    """Detects faces in an image."""
    vision_client = vision.Client()

    with io.open(path, 'rb') as image_file:
        content = image_file.read()

    image = vision_client.image(content=content)
    
    faces = image.detect_faces()
    image_info();
    for face in faces:
        image_info();
        vertices = (['({},{})'.format(bound.x_coordinate, bound.y_coordinate)
            for bound in face.bounds.vertices])

        predictedObject = "{}".format(','.join(vertices))
        name = (objectTag.replace(".jpg","")).split("/")[1]
        accuracy = ('anger: {}'.format(face.emotions.anger)).replace("Likelihood.","")
        data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
        print(data)
        export_csv(data)
        database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight)
        accuracy = ('joy: {}'.format(face.emotions.joy)).replace("Likelihood.","")
        data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
        print(data)
        export_csv(data)
        database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight)
        accuracy = ('surprise: {}'.format(face.emotions.surprise)).replace("Likelihood.","")
        data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
        print(data)
        export_csv(data)
        database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight)

def detect_labels(path):
    type1,objectTag,timeProcessed,imgHeight,imgWidth = image_info();
    """Detects labels in the file."""
    vision_client = vision.Client()

    with io.open(path, 'rb') as image_file:
        content = image_file.read()

    image = vision_client.image(content=content)

    labels = image.detect_labels()
    name = (objectTag.replace(".jpg","")).split("/")[1]

    for label in labels:
        predictedObject = str(label.description);
        accuracy = str(label.score);
        data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
        print(data)
        export_csv(data)
        database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight)



def detect_text(path):
    type1,objectTag,timeProcessed,imgHeight,imgWidth = image_info();
    """Detects text in the file."""
    vision_client = vision.Client()

    with io.open(path, 'rb') as image_file:
        content = image_file.read()

    image = vision_client.image(content=content)
    name = (objectTag.replace(".jpg","")).split("/")[1]
    texts = image.detect_text()
    print(texts)
    name = (objectTag.replace(".jpg","")).split("/")[1]
    for text in texts:
        predictedObject = ("{}".format(text.description).replace("\n","  "));

        vertices = (['({},{})'.format(bound.x_coordinate, bound.y_coordinate)
                    for bound in text.bounds.vertices])

        #print('bounds: {}'.format(','.join(vertices)))
        accuracy = "NaN";
        data = {"Timestamp":timeProcessed,"Object": objectTag, "Guess": predictedObject, "Accuracy": str(accuracy), "Width": str(imgWidth), "Height": str(imgHeight), "Service": type1};
        print(data)
        export_csv(data)
        predictedObject = predictedObject.replace("product-images/","")
        database_submit(name,type1,predictedObject,accuracy,imgWidth,imgHeight)
        exit();


def detect_document(path):
    type1,objectTag,timeProcessed,imgHeight,imgWidth = image_info();
    """Detects document features in an image."""
    vision_client = vision.Client()

    with io.open(path, 'rb') as image_file:
        content = image_file.read()

    image = vision_client.image(content=content)

    document = image.detect_full_text()

    for page in document.pages:
        for block in page.blocks:
            block_words = []
            for paragraph in block.paragraphs:
                block_words.extend(paragraph.words)

            block_symbols = []
            for word in block_words:
                block_symbols.extend(word.symbols)

            block_text = ''
            for symbol in block_symbols:
                block_text = block_text + symbol.text

            print('Block Content: {}'.format(block_text))
            print('Block Bounds:\n {}'.format(block.bounding_box))

def run_local(args):
    if args.command == 'faces':
        detect_faces(args.path)
    elif args.command == 'labels':
        detect_labels(args.path)
    elif args.command == 'text':
        detect_text(args.path)
    elif args.command == 'document':
        detect_document(args.path)



if __name__ == '__main__':
    parser = argparse.ArgumentParser(
        description=__doc__,
        formatter_class=argparse.RawDescriptionHelpFormatter)
    subparsers = parser.add_subparsers(dest='command')

    detect_faces_parser = subparsers.add_parser(
        'faces', help=detect_faces.__doc__)
    detect_faces_parser.add_argument('path')
    
    detect_labels_parser = subparsers.add_parser(
        'labels', help=detect_labels.__doc__)
    detect_labels_parser.add_argument('path')

    detect_text_parser = subparsers.add_parser(
        'text', help=detect_text.__doc__)
    detect_text_parser.add_argument('path')

    document_parser = subparsers.add_parser(
        'document', help=detect_document.__doc__)
    document_parser.add_argument('path')

    args = parser.parse_args()

    run_local(args)