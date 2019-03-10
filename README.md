# URL generator for darthsim/imgproxy

See https://github.com/DarthSim/imgproxy.

This is a small package which allows for generating URLs for your images that are resized by imgproxy:

```php
$builder = new UrlBuilder("http://localhost:8080", "< your HEX key >", "< you HEX salt >");

// Generate imgproxy URL for an image, resizing to 300x200 with default settings (*)
$url = $builder->build("http://myimages.localhost/cats.jpg", 300, 200);
echo $url->toString();

// Customize URL params
$url->setFit("fill")
  ->setWidth(1200)
  ->setHeight(1200)
  ->setGravity("no")
  ->setEnlarge(true);
  
echo $url->toString();
```

(*) default settings:
 * Fit: _fit_
 * Gravity: _sm_ (smart)
 * Enlarge: _0_ (do not enlarge images)
 
 Please refer to imgproxy docs for parameter descriptions and possible values.