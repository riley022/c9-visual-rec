find ./product-images/. | grep -v ".jpg" | grep -v ".sh" | tail -n +2 | while IFS= read -r FILENAME
do
	convert "${FILENAME}" "${FILENAME%.*}.jpg"
done
