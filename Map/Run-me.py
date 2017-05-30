from glob import glob
import subprocess
from random import randint
import os
import sys

photo = sys.argv[1];
photo_name = ('%04d'%int(photo.replace(".jpg","")))+"-Store.jpg"
os.system("cp /home/ubuntu/workspace/product-images/"+ photo + " ./"+photo_name)

#run python script colour-changer.py
print("Running Colour Changer....")
subprocess.call(['sh','-c','python colour-changer.py'])

photo = sys.argv[1]

#run OCR on each folder individually
for i in range(0, 1):
	print("Running OCR Script for Store: " + photo)
	string = "python ocr-google.py ./" + photo_name.replace("-Store.jpg","")+"/"
	print(string)
	subprocess.call(['sh','-c',string])
	
	file_name = "List_"+photo_name.replace("-Store.jpg","") +".csv"
	file = open(file_name, "r");
	file2 = open(file_name.replace(".csv","_output.csv"), "w+");
	for x in file.readlines():
		random_number = randint(0,10)
		if random_number > 7:
			string_to_add = ",Downn"
		else:
			string_to_add = ",Upp"
		file2.writelines([''.join([x.strip("\n"), string_to_add, '\n'])]) 
		
file2.close()
file.close()

os.system("rm "+file_name)
os.system("rm -R "+photo_name.replace("-Store.jpg",""))
os.system("mv /home/ubuntu/workspace/Map/"+ file_name.replace(".csv","_output.csv")+ " /home/ubuntu/workspace/exampleData/"+file_name)
os.system("mv /home/ubuntu/workspace/Map/"+ photo_name + " /home/ubuntu/workspace/exampleData/")




