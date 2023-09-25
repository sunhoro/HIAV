<?php
# hide all warnings#
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
# hide all warnings#

$directoryPath     = 'files/image';
$jsonFiles         = 'files/label_summary';


$paginationPerPage = 20;
if ($_REQUEST['action'] == 'getimages') {
    $page     = ($_REQUEST['pagenum']) ?: 1;
    $images   = scandir($directoryPath);
    $maxPages = ceil(count($images) / $paginationPerPage);
    $counter  = 1;
    if (count($images) > $paginationPerPage) {
        $images = array_slice($images, $paginationPerPage * intval($page) - 1, $paginationPerPage);
    }
    ?>

<head>
  <style>
    .table.images {
      margin-top: 20px;
    }
  </style>
</head>
<body>


<div style="margin-left: 20px; margin-top: 20px;">
<h2 style="font-size: 28px; font-weight: bold; text-align: left;">List of Images</h2>
<table class="table images">
    <?php
    foreach ($images as $image) {
        if ($image != '.' && $image != '..' && strpos($image, '_seg') === false) {
            $imageNameWithOutExtension = '';
            $imageNameArray            = explode('.', $image);
            if (!empty($imageNameArray)) {
                $imageNameWithOutExtension = $imageNameArray[0];
            }
            $imageNameWithOutExtension = str_replace($directoryPath . '/', '', $imageNameWithOutExtension);
            $image                     = $directoryPath . '/' . $image;
            ?>
            <tr onclick="showcontent('<?php echo $image; ?>',this)">
                <td><input type="checkbox" name="selected_images[]" value="<?php echo $image; ?>" style="transform: scale(2.0);"></td>
                <td style="cursor: pointer;">
                    <img src="<?php echo $image; ?>"
                         height="100" width="100">
                    <span style="margin-left: 10px;"><?php echo $imageNameWithOutExtension; ?></span>
                </td>
            </tr>
            <?php
            $counter++;
        }
    }
    ?>
</table>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php
            if ($page > 1) {
                ?>
                <li class="page-item"><a onclick="paginate(<?php echo $page - 1 ?>)" class="page-link">previous </a>
                </li>
                <?php
            }
            if ($page < $maxPages) {
                ?>
                <li class="page-item"><a onclick="paginate(<?php echo $page + 1 ?>)" class="page-link">Next</a>
                </li>
            <?php }
            ?>
        </ul>
    </nav>
    <?php
}

if ($_POST && $_GET['action'] == 'filter') {
    if (($open = fopen("files/building_info.csv", "r")) !== FALSE) {

        while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            $array[] = $data;
        }

        fclose($open);
    }

    $counter        = 0;
    $buildingInfo   = [];
    $buildingDamage = [];
    $images         = [];
    // find the row with matching image
    foreach ($array as $key => $row) {
        if ($key == 0) {
            foreach ($row as $key => $headerValue) {
                $searchValue = $_POST[preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $headerValue)];
                foreach ($array as $newKey => $rowNew) {
                    if ($newKey > 0) {
                        foreach ($rowNew as $resultKey => $rowResult) {
                            if (!empty($rowResult) && !empty($searchValue)) {
                                if ($rowResult == $searchValue) {
                                    $images[$rowNew[5]] = $rowNew[5] . '.jpg';
                                }
                            }
                        }
                    }
                }

            }
        }
    }
    $files = scandir($jsonFiles);
    foreach ($files as $fileName) {
        $json       = file_get_contents($jsonFiles . '/' . $fileName);
        $jsonDecode = json_decode($json, true);
        if (!empty($jsonDecode)) {
            foreach ($jsonDecode as $key => $info) {
                $key = str_replace(' ', '_', $key);
                $value = $_POST[$key];
                $value = floatval($value);
                $info = floatval($info);
                if (!empty($value)) {
                    if ($info >= $value) {
                        $imageNameWithOutExtension = '';
                        $imageNameArray            = explode('.', $fileName);
                        if (!empty($imageNameArray)) {
                            $imageNameWithOutExtension = $imageNameArray[0];
                        }
                        $imageNameWithOutExtension = str_replace($directoryPath . '/', '', $imageNameWithOutExtension);

                        $images[$imageNameWithOutExtension] = $imageNameWithOutExtension . '.jpg';
                    }
                }
            }
        }
    }
    if (!empty($images)) {
        ?>
        <table class="table images">
            <?php
            foreach ($images as $image) {
                if ($image != '.' && $image != '..' && strpos($image, '_seg') === false) {
                    $imageNameWithOutExtension = '';
                    $imageNameArray            = explode('.', $image);
                    if (!empty($imageNameArray)) {
                        $imageNameWithOutExtension = $imageNameArray[0];
                    }
                    $imageNameWithOutExtension = str_replace($directoryPath . '/', '', $imageNameWithOutExtension);
                    $image                     = $directoryPath . '/' . $image;
                    ?>
                    <tr onclick="showcontent('<?php echo $image; ?>',this)">
                        <td style="cursor: pointer;">
                            <img src="<?php echo $image; ?>"
                                 height="100" width="100">
                            <span style="margin-left: 10px;"><?php echo $imageNameWithOutExtension; ?></span>
                        </td>
                    </tr>
                    <?php
                    $counter++;
                }
            }
            ?>
        </table>
        <?php
    } else {
        ?>
        <h2 class="mt-5" style="text-align: center"> No Images Found.</h2>
        <?php
    }
    ?>
    <?php
}
if ($_REQUEST['action'] == 'individualimage') {
    $image                     = $_REQUEST['image'];
    $imageNameWithOutExtension = '';
    $imageNameArray            = explode('.', $image);
    if (!empty($imageNameArray)) {
        $imageNameWithOutExtension = $imageNameArray[0];
    }
    $imageNameWithOutExtension = str_replace($directoryPath . '/', '', $imageNameWithOutExtension);
    if (($open = fopen("files/building_info.csv", "r")) !== FALSE) {

        while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
            $array[] = $data;
        }

        fclose($open);
    }
    $json       = file_get_contents("files/label_summary/" . $imageNameWithOutExtension . ".json");
    $jsonDecode = json_decode($json, true);
    if (!empty($jsonDecode)) {
        ksort($jsonDecode);
    }


    $counter        = 0;
    $buildingInfo   = [];
    $buildingDamage = [];
    // find the row with matching image
    foreach ($array as $key => $row) {
        if ($key == 0) {
            foreach ($row as $headerValue) {
                $header[] = ucfirst(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', str_replace('_', ' ', $headerValue)));
            }
        }
        if ($row[5] == $imageNameWithOutExtension) {
            foreach ($row as $key => $indexValue) {
                if ($key != 5) {
                    if ($key < 20) {
                        $buildingInfo [$header[$key]] = ($indexValue) ?: 'n/a';
                    } else {
                        $buildingDamage[$header[$key]] = ($indexValue) ?: 'n/a';
                    }
                }
            }
        }
        $counter++;
    }
    ?>

<div class="main_image">
  <div>
    <span style="display:block; text-align:center; font-weight:bold; font-size:20px;">Original Image</span>
    <img src="<?php echo $image; ?>" id="main_product_image"
         height="500" width="500" onclick="toggleImageLabel(this)">
  </div>
  <div>
    <span style="display:block; text-align:center; font-weight:bold; font-size:20px;">Segmentation Mask</span>
    <img src="<?php echo $directoryPath . '/' . $imageNameWithOutExtension . '_seg.jpg'; ?>" id="main_product_image"
         height="500" width="500" onclick="toggleImageLabel(this)">
  </div>
</div>


    <div class="p-3 right-side">
        <div class="row">
            <div class="col-md-4">
                <h5>Building Info</h5>
                <ul class="list-group">
                    <?php foreach ($buildingInfo as $key => $info) { ?>
                        <li class="list-group-item"><b><?php echo $key; ?>:</b> <?php echo $info; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Building Damage</h5>
                <ul class="list-group">
                    <?php foreach ($buildingDamage as $key => $info) { ?>
                        <li class="list-group-item"><b><?php echo $key; ?>:</b> <?php echo ($key === 'Total damage rating') ? '<strong style="color:red">'.$info.'</strong>' : $info; ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Primary Label</h5>
                <ul class="list-group">
                    <?php foreach ($jsonDecode as $key => $info) { ?>
                        <li class="list-group-item"><b><?php echo $key; ?>:</b> <?php echo ($info > 10) ? '<span style="color:blue;font-weight:bold;text-decoration:underline">'.$info.'</span>' : $info; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <?php

}
?>