# Simple transitions between 2 images of the same size.

This class will help you generate an animated gif that transitions from one image (jpg) to another image (jpg).

Both images will have to be of the same size.

## Installation

```bash
    composer require digitalkreativ/php-simple-gif-transitions
```

## Example

```php
<?php

try {
    $config = new \Digitalkreativ\SimpleGifTransitions\Library\Config();

    $config->setTempDirectory( '<full-path-to-tmp-dir>' )
        ->setDebug( true )
        ->setImageDriver( \Digitalkreativ\SimpleGifTransitions\Library\Config::IMAGE_DRIVER_GD );


    $generator = new \Digitalkreativ\SimpleGifTransitions\GifGenerator( $config );
    $generator->setScenario( new \Digitalkreativ\SimpleGifTransitions\Scenarios\RevealSlideBottom() );
    $generator->setFromImage( '<full-path-to-jpg>'  )
        ->setToImage( '<full-path-to-jpg>' )
        ->generate( '<full-path-to-jpg-to-generate>' );

} catch ( Exception $ex ){
    echo $ex->getMessage() . PHP_EOL;
}

```


## Components used

* [erikvdven/php-gif](https://github.com/ErikvdVen/php-gif)
* [intervention/image](https://github.com/Intervention/image)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
