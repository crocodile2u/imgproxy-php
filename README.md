# URL generator for darthsim/imgproxy

See https://github.com/DarthSim/imgproxy.

> STARTING FROM v2.0 imgproxy-php supports advanced URL generation. See below.  
> **imgproxy-php** v2.0 is fully backwards compatible with v1.0, you should not have any issues with upgrading.  
> If those issues still occur with you, you're welcome to file a bug report, and even more welcome to create a pull request.

This is a small package which allows for generating URLs for your images that are resized by imgproxy:

```php
$builder = new UrlBuilder("http://localhost:8080", "< your HEX key >", "< you HEX salt >");

// Generate imgproxy URL for an image, resizing to 300x200 with default settings (*)
$url = $builder->build("http://myimages.localhost/cats.jpg", 300, 200);

// By default, the URL is generated in basic mode
echo $url->toString();

// Customize URL params
$url->setFit("fill")
  ->setWidth(1200)
  ->setHeight(1200)
  ->setGravity("no")
  ->setEnlarge(true);

// switch to advanced URL mode with tons of extra features, superior to the basic mode:
$url->useAdvancedMode();

// set processing options:
$url->options()->withDpr(2)/* -> chain more with<FEATURE>() calls -> ... */;
  
echo $url->toString();
```

(*) default settings:
 * Mode: basic (apparently, basci mode is deprecated by imgproxy and possibly will be removed in future release)
 * Fit: _fit_
 * Gravity: _sm_ (smart)
 * Enlarge: _0_ (do not enlarge images)
 
 Please refer to imgproxy docs for parameter descriptions and possible values.

# Imgproxy PRO features

Certain features of Imgproxy are only available in the PRO
version. Please refer to the docs https://docs.imgproxy.net/generating_the_url_advanced and make notice of the `PRO` label on certain processing options. **Using those options on a regular (non-PRO) instance of Imgproxy will result in "Invalid URL" response**. 
 
# Testing in "real life"

In the root folder you will find `docker-compose.yml` file which will start `imgproxy` instance on port 8080. There is also a composer script `generate` with parameters:
 * base : string base URL
 * key : string IMGPROXY_KEY
 * salt : string IMGPROXY_SALT
 * source : string source image URL
 * width : int width
 * height : int height
 * advanced (no value required)

Once you started your local docker-compose stack with `docker-compose up`, you may call this script with the
following command:

```sh
composer run-script generate -- \
  --base <imgproxy base URL> \
  --source <source image URL> \
  --key <IMGPROXY_KEY> \
  --salt <IMGPROXY_SALT>
  --width <width> \
  --height <height> \
  --advanced

# example:  
composer run-script generate -- \
  --base http://localhost:8080 \
  --source http://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Cailliau_Abramatic_Berners-Lee_10_years_WWW_consortium.png/330px-Cailliau_Abramatic_Berners-Lee_10_years_WWW_consortium.png \
  --key 943b421c9eb07c830af81030552c86009268de4e532ba2ee2eab8247c6da0881 \
  --salt 520f986b998545b4785e0defbc4f3c1203f22de2374a3d53cb7a7fe9fea309c5
```

Hear, the KEY & SALT are valid for the imgproxy instance started by provided `docker-compose.yml`.
You can also test URL generation for arbitrary custom imgproxy installation by providing relevant `base`, `key` & `salt`. Happy testing!