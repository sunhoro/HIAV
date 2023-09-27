# HIAV (Hurricane Image Analysis Viewer)

![image](https://github.com/sunhoro/HIAV/assets/58085880/b21f2c0e-128b-4f54-8c61-9be244a5d3cd)
![image](https://github.com/sunhoro/HIAV/assets/58085880/76a4f556-9a51-451b-89f1-34f5e54bcad2)

## Background
Natural disaster data repositories centralize and manage extensive datasets associated with various calamities, bolstering disaster preparedness, response, and recovery efforts. However, many of these repositories primarily focus on data storage and sharing, without truly harnessing the power of modern data analytics and machine learning for data curation. A significant challenge persists: many repositories act as mere raw data storage units without curating and processing data. Current systems have limitations in retrieving specific data sets, leading to significant effort in sorting and classifying data. Despite the vast amount of information available, there's no standard framework that harnesses AI computing capabilities to fully utilize this data. To address these challenges, this page introduces the Hurricane Image Analysis Viewer (HIAV), a novel curated data storing and viewing structure. The HIAV employs processed primary labels obtained from a large-image curation framework developed and manual damage assessment data is integrated with the building component segmentation mask, linking these two data types holistically and enabling a comprehensive visualization. 

## How to run HIAV
1. Prepare Data
As seen in the same data folder, you need (a) an image, (b) an image with object detection results, (c) CSV file on manual damage assessment data, and (4) primary labels. For object detection, any model such as Mask RCNN, YOLO, or DETR can be used as long as the proper JSON file is retrieved. Details on processing primary labels will be updated with an upcoming journal paper. Lastly, manual damage assessment data should be prepared in a similar manner.
2. Download XAMPP: https://www.apachefriends.org/download.html
3. Move all necessary data to C:\xampp\htdocs
4. Start the module and activate it on an internet browser, for example, localhost/HIAV.
