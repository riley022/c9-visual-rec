import argparse
# [START detect_text]
import base64
import os
import re
import sys
import json

from googleapiclient import discovery
from googleapiclient import errors
import nltk
from nltk.stem.snowball import EnglishStemmer
from oauth2client.client import GoogleCredentials

DISCOVERY_URL = 'https://{api}.googleapis.com/$discovery/rest?version={apiVersion}'  # noqa
BATCH_SIZE = 10
text = ""
array_string = {}
array_coord = {}
final_array = []
final_array_coord = []
SID = ""

class VisionApi:
    """Construct and use the Google Vision API service."""

    def __init__(self, api_discovery_file='vision_api.json'):
        self.credentials = GoogleCredentials.get_application_default()
        self.service = discovery.build(
            'vision', 'v1', credentials=self.credentials,
            discoveryServiceUrl=DISCOVERY_URL)

    def detect_text(self, input_filenames, num_retries=3, max_results=6):
        """Uses the Vision API to detect text in the given file.
        """
        images = {}
        for filename in input_filenames:
            with open(filename, 'rb') as image_file:
                images[filename] = image_file.read()

        batch_request = []
        for filename in images:
            batch_request.append({
                'image': {
                    'content': base64.b64encode(
                            images[filename]).decode('UTF-8')
                },
                'features': [{
                    'type': 'TEXT_DETECTION',
                    'maxResults': max_results,
                }]
            })
        request = self.service.images().annotate(
            body={'requests': batch_request})

        try:
            responses = request.execute(num_retries=num_retries)
            if 'responses' not in responses:
                return {}
            text_response = {}
            for filename, response in zip(images, responses['responses']):
                if 'error' in response:
                    print("API Error for %s: %s" % (
                            filename,
                            response['error']['message']
                            if 'message' in response['error']
                            else ''))
                    continue
                if 'textAnnotations' in response:
                    text_response[filename] = response['textAnnotations']
                else:
                    text_response[filename] = []

            return text_response
        except errors.HttpError as e:
            print("Http Error for %s: %s" % (filename, e))
        except KeyError as e2:
            print("Key error: %s" % e2)
# [END detect_text]



def batch(iterable, batch_size=BATCH_SIZE):
    """Group an iterable into batches of size batch_size.
    >>> tuple(batch([1, 2, 3, 4, 5], batch_size=2))
    ((1, 2), (3, 4), (5))
    """
    b = []
    for i in iterable:
        b.append(i)
        if len(b) == batch_size:
            yield tuple(b)
            b = []
    if b:
        yield tuple(b)


def main(photo):
    # Create a client object for the Vision API
    vision = VisionApi()
    # Create an Index object to build query the inverted index.
    #index = Index()
    filenames = [100]
    filenames[0] = photo;
    filenames[1] = photo.replace(".jpg","/") + photo.replace(".jpg","-blue.jpg");
    for filenames in range(0,1):
        texts = vision.detect_text(filenames)
        formatted = str(texts).replace("u","")
        #print(json.dumps(texts,sort_keys=True, indent=3))
        u = 0
        for i in range(0,1):
            print(filenames[i])
            SID = filenames[i].split(".")[0]
            for j in range(1,100):
                try:
                    array_string[j-1] = texts[filenames[i]][j]['description'].encode('utf-8').strip().replace(" ","").replace("a","A").replace("x","X").replace("p","P").replace("O","0").replace("o","0").replace("i","1")
                    y = int(texts[filenames[i]][j]['boundingPoly']['vertices'][0]['y'])
                    x_1 = int(texts[filenames[i]][j]['boundingPoly']['vertices'][0]['x'])
                    x_2 = int(texts[filenames[i]][j]['boundingPoly']['vertices'][1]['x'])
                    x = x_1 +((x_2-x_1)/2)
                    array_coord[j-1] = (str(x),str(y))
                except:
                    continue

            string = ""
            for k in range(0,len(array_string)):
                if(array_string[k].startswith("AP") and len(array_string[k]) >= 4):
                    string = "AP" + str(array_string[k][2]) + str(array_string[k][3]) 
                    string1 = "AXP" + str(array_string[k][2]) + str(array_string[k][3]) 
                    if string not in final_array and string1 not in final_array:
                        try:
                            int(array_string[k][2])
                            try:
                                int(array_string[k][3])
                                final_array.append("AP" + str(array_string[k][2]) + str(array_string[k][3]))
                                final_array_coord.append(array_coord[k])
                            except:
                                print("")
                        except:
                            print("")

                elif(array_string[k].startswith("AXP") and len(array_string[k]) >= 5):
                    string = "AXP" + str(array_string[k][3]) + str(array_string[k][4]) 
                    string1 = "AP" + str(array_string[k][3]) + str(array_string[k][4]) 
                    if string not in final_array and string1 not in final_array:
                        try:
                            int(array_string[k][3])
                            try:
                                int(array_string[k][4])
                                final_array.append("AXP" + str(array_string[k][3]) + str(array_string[k][4]))
                                final_array_coord.append(array_coord[k])
                            except:
                                print("")
                        except:
                            print("")

                #Possible Improvements e.g.
                elif(array_string[k].startswith("P") and len(array_string[k]) == 3):
                    string1 = "AP" + str(array_string[k][1]) + str(array_string[k][2]) 
                    string2 = "AXP" + str(array_string[k][1]) + str(array_string[k][2]) 
                    if (string1 not in final_array) and (string2 not in final_array):
                        try:
                            int(array_string[k][1])
                            try:
                                int(array_string[k][2])
                                final_array.append(string1)
                                final_array_coord.append(array_coord[k])
                            except:
                                print("")
                        except:
                            print("")

        print(final_array)
        print(final_array_coord)
        print(SID)
        file_name = SID + ".csv"
        out = open(file_name, 'w+')
        for i in range(0,len(final_array)):
            out.write(final_array[i] + "," + final_array_coord[i][0] + "," + final_array_coord[i][1] +"\n")
        out.flush()
        out.close()

# [END get_text]

if __name__ == '__main__':
    parser = argparse.ArgumentParser(
        description='Detects text in the images in the given directory.')
    parser.add_argument(
        'input_directory',
        help='the image directory you\'d like to detect text in.')
    args = parser.parse_args()
    photo = sys.argv[1];
    main(photo)