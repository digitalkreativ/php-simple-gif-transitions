<?php

namespace Digitalkreativ\SimpleGifTransitions\Scenarios;


use Digitalkreativ\SimpleGifTransitions\Library\Config;
use Intervention\Image\ImageManager;

class PushTop extends BaseScenario
{

    protected $_name = 'toImage pushes fromImage to the top';


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

        $counter = $height;
        $frame = 0;
        while( $counter > 0 ){

            $counter-= $frame * $this->_pixelStep;

            if( $counter <= 0 ){
                $counter = 0;
            }

            $img = $imageManager->canvas( $width, $height );

            $previous = $imageManager->make( $fromImage );
            $result = $imageManager->make( $toImage );

            $img->insert( $previous, 'top-left', 0, $counter - $height );
            $img->insert( $result, 'top-left', 0, $counter );

            $img->save(  $this->_getFrameFilePath( $tempDirectory, $frame ), $frameImageQuality );

            if( $config->isDebug() ){
                echo $frame . PHP_EOL;
            }

            $frame++;
        }

        return $frame;

    }



}