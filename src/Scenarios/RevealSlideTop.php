<?php

namespace Digitalkreativ\SimpleGifTransitions\Scenarios;


use Digitalkreativ\SimpleGifTransitions\Library\Config;
use Intervention\Image\ImageManager;

class RevealSlideTop extends BaseScenario
{

    protected $_name = 'Reveal toImage, slide fromImage to the top';


    /**
     * @param ImageManager $imageManager
     * @param Config $config
     * @param $fromImage
     * @param $toImage
     * @param $width
     * @param $height
     * @return int
     */
    public function frames( ImageManager $imageManager, Config $config, $fromImage, $toImage, $width, $height )
    {

        // Inside this function you generate the frame images in the temp directory
        // After that you return the directory.

        $tempDirectory = $config->getTempDirectory();
        $frameImageQuality = $this->_qualityFrameImage;
        if( $config->getFrameImageQuality() > $frameImageQuality ){
            $frameImageQuality = $config->getFrameImageQuality();
        }

        if( $config->isDebug() ){
            echo 'from image: ' . $fromImage . PHP_EOL;
            echo 'to image: ' . $toImage . PHP_EOL;
            echo 'frame img q: ' . $frameImageQuality . PHP_EOL;
            echo 'tempdir: ' . $tempDirectory . PHP_EOL;
        }

        $counter = 0;
        $frame = 0;
        while( $counter > -$height ){

            $counter-= $frame * $this->_pixelStep;

            // create new Intervention Image
            $img = $imageManager->make( $toImage );

            // create a new Image instance for inserting
            $overlay = $imageManager->make( $fromImage );
            $img->insert( $overlay, 'top-left', 0, $counter );

            $img->save(  $this->_getFrameFilePath( $tempDirectory, $frame ), $frameImageQuality );

            if( $config->isDebug() ){
                echo $frame . PHP_EOL;
            }

            $frame++;
        }

        return $frame;

    }



}