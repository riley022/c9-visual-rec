import sys
import os
import numpy as np
import cv2

def rgb2gray(rgb):
	return np.dot(rgb[...,:3],[1.3,1.3,1.3])

def ensure_dir(f):
    d = os.path.dirname(f)
    if not os.path.exists(d):
        os.makedirs(d)

str1 = "-"
str2 = "/"
imagesdir = os.path.abspath(os.path.join(os.curdir))
print "Searching for images in {} Directory)".format(imagesdir)
exts = ['.jpg']
for dirname, dirnames, filenames in os.walk("./"):
	for filename in filenames:
		if filename.endswith(".jpg") and len(filename) < 15:
			name, ext = os.path.splitext(filename)
			img2 = cv2.imread(os.path.join(dirname, filename))
			print(filename)
			red = img2[:,:,2]
			green = img2[:,:,1]
			blue = img2[:,:,0]
			for values, color, channel in zip((red, green, blue), ('red', 'green', 'blue'), (2,1,0)):
				img = np.zeros((values.shape[0], values.shape[1], 3), dtype = values.dtype) 
				img[:,:,channel] = values
				img = rgb2gray(img)
				SID = name[0] + name[1] + name[2] + name[3] 
				print("Saving Image: {}.".format(str2 + SID + str2 + name + str1 +color+ext))
				ensure_dir(imagesdir+"/"+SID+"/")
				cv2.imwrite(os.path.join(imagesdir+"/"+SID+"/" + name+str1 +color+ext), img)
			print("Saving Image: {}.".format(str2 + SID + str2 + name + str1 +"origional"+ext))
			cv2.imwrite(os.path.join(imagesdir+"/"+SID+"/" + name+ str1 + "origional" +ext), img2)