# Lost
Level - Hard

Description:
```
Help! My friend is lost and all I have is this folder of photos.

https://byu.box.com/s/ld7tcfr2ievkrusdcgba9z0az51g6dt0
```

## Writeup
In this Capture The Flag (CTF) challenge, the participants are given a folder containing multiple photos. Each photo has GPS coordinates embedded in its metadata. The objective of the challenge is to extract these coordinates, plot them on a map, and reveal the hidden CTF flag.

### General Solution Guide:

1. **Analyze the photos**: Begin by inspecting the photos to understand the challenge. Analyze the metadata to determine if it contains GPS coordinates.
1. **Extract GPS coordinates**: Use a programming language like Python with a library such as piexif or exifread to extract the GPS coordinates from the metadata of each photo.
1. **Convert coordinates**: If necessary, convert the GPS coordinates from degrees, minutes, and seconds (DMS) to decimal format.
1. **Plot coordinates on a map**: Use a mapping tool or library like Google Maps API, OpenStreetMap, or Leaflet to plot the coordinates on a map. Connect the points in the order they appear in the folder to form a path.
1. **Analyze the plotted path**: Visually inspect the path created by the plotted coordinates. The path should form a recognizable shape, text, or pattern that reveals the CTF flag.
1. **Submit the flag**: Once the flag is identified, submit it to complete the challenge.

### Sample Python Code for Extracting GPS Coordinates
```python
import os
import csv
from PIL import Image
from PIL.ExifTags import TAGS, GPSTAGS

def extract_gps_info(img):
    exif_data = img._getexif()
    gps_info = {}
    if exif_data:
        for tag, value in exif_data.items():
            tag_name = TAGS.get(tag, tag)
            if tag_name == 'GPSInfo':
                for gps_tag in value:
                    sub_tag = GPSTAGS.get(gps_tag, gps_tag)
                    gps_info[sub_tag] = value[gps_tag]

    if 'GPSLatitude' in gps_info and 'GPSLongitude' in gps_info:
        return gps_info
    else:
        return None

def get_decimal_coordinates(info):
    lat_data = info['GPSLatitude']
    lat = float(lat_data[0]) + (float(lat_data[1]) / 60) + (float(lat_data[2]) / 3600)
    if info['GPSLatitudeRef'] == 'S':
        lat = -lat

    lon_data = info['GPSLongitude']
    lon = float(lon_data[0]) + (float(lon_data[1]) / 60) + (float(lon_data[2]) / 3600)
    if info['GPSLongitudeRef'] == 'W':
        lon = -lon

    return lat, lon

def main():
    folder_path = "Images"
    csv_path = "gps_coordinates.csv"
    
    with open(csv_path, mode='w', newline='') as csv_file:
        writer = csv.writer(csv_file)
        writer.writerow(["Latitude", "Longitude"])
        
        for filename in os.listdir(folder_path):
            if filename.endswith(".jpeg") or filename.endswith(".jpg"):
                image_path = os.path.join(folder_path, filename)
                with Image.open(image_path) as img:
                    gps_info = extract_gps_info(img)
                    if gps_info:
                        lat, lon = get_decimal_coordinates(gps_info)
                        writer.writerow([lat, lon])

if __name__ == '__main__':
    main()
```

**Flag** - `byuctf{@CleverUSB}`