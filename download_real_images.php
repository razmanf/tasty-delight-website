<?php
$folder = __DIR__ . '/storage/app/public/products/';

if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$images = [
    'burger.jpg' => 'https://cdn.pixabay.com/photo/2020/05/03/07/54/burger-5124531_1280.jpg',
    'pizza.jpg'  => 'https://cdn.pixabay.com/photo/2016/03/05/19/02/hamburger-1238246_1280.jpg',
    'coffee.jpg' => 'https://cdn.pixabay.com/photo/2016/11/29/04/16/coffee-1869745_1280.jpg',
    'dessert.jpg'=> 'https://cdn.pixabay.com/photo/2017/05/07/08/56/dessert-2291408_1280.jpg',
    'salad.jpg'  => 'https://cdn.pixabay.com/photo/2018/06/19/19/02/salad-3486849_1280.jpg',
    'default.jpg'=> 'https://cdn.pixabay.com/photo/2015/10/22/17/49/no-image-1001563_1280.png',
];

foreach ($images as $filename => $url) {
    $path = $folder . $filename;
    if (!file_exists($path)) {
        file_put_contents($path, file_get_contents($url));
        echo "Downloaded $filename\n";
    } else {
        echo "$filename already exists\n";
    }
}
