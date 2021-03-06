<?php

namespace Digitalkreativ\SimpleGifTransitions\Scenarios;


use Digitalkreativ\SimpleGifTransitions\Library\Config;
use Intervention\Image\ImageManager;


/**
 * Class BaseScenario
 *
 * @package Digitalkreativ\SimpleGifTransitions\Scenario
 */
class BaseScenario
{

    protected $_name = 'BaseScenario';

    protected $_pixelStep = 55; // Frame times pixel step defines the number of pixels the image will move from frame to frame.
    protected $_qualityFrameImage = 70;

    public function __construct()
    {

    }

    /**
     * This function generates the frames in a directory, this is the one to change if you want your own scenarios
     *
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

        /*
         |---------------------------------
         | EXAMPLE SCENARIO
         |---------------------------------
         */

        $counter = 0;
        $frame = 0;
        while( $counter < $width ){
            $counter+= $frame * $this->_pixelStep;

            // create new Intervention Image
            $img = $imageManager->make( $toImage );

            // create a new Image instance for inserting
            $overlay = $imageManager->make( $fromImage );
            $img->insert( $overlay, 'top-left', $counter);

            $img->save(  $this->_getFrameFilePath( $tempDirectory, $frame ), $frameImageQuality );

            if( $config->isDebug() ){
                echo $frame . PHP_EOL;
            }

            $frame++;
        }

        return $frame;
    }

    public function getName()
    {
        return $this->_name;
    }


    protected function _getFrameFilePath( $tempDirectory, $frame )
    {
        $frameFileName = $frame;
        while( strlen( $frameFileName ) < 3 ){
            $frameFileName = '0' . $frameFileName;
        }

        return  $tempDirectory . DIRECTORY_SEPARATOR . $frameFileName . '.jpg';
    }

}