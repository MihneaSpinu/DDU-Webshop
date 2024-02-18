<?php
$design = false;
$categories = array();
if (Input::get('category') == 'clothing') {
    $chosenCategories = array('t-shirts', 'hoodies', 'hats');
} else if (Input::get('category') == 'homewares') {
    $chosenCategories = array('mugs', 'plushies', 'posters');
} else if (Input::get('category') == 'accessories') {
    $chosenCategories = array('accessories', 'mouse pads', 'stickers');
} else {
    $design = true;
}
if ($design) {
    $designs = array(
        'BroomSweep Logo',
        'Finite Abys',
        'Hero',
        'Villain',
        'Zorg',
        'Map',
        'Sword Slash'
    );
    shuffle($designs);

    foreach ($designs as $design) {
        $allDesigns[] = array(
            'design_name' => $design,
            'image_path' => 'productImages/' . $design . '.png',
            'design_description' => 'Browse our ' . $design . ' collection.'
        );
        //If image path does not exist, set it to a placeholder image.
        if (!file_exists($allDesigns[count($allDesigns) - 1]['image_path'])) {
            $allDesigns[count($allDesigns) - 1]['image_path'] = 'productImages/placeholder.png';
        }
    }
} else {
    foreach ($chosenCategories as $chosenCategory) {        
        $category = Database::getInstance()->get('categories', array('category_name', '=', $chosenCategory))->first();
        $categories[] = $category;
    }    
    $chosenCategory = ucfirst(Input::get('category'));
}
