<?php

namespace Digitalkreativ\SimpleGifTransitions\Library;

/**
 * Class Config
 *
 * @package Digitalkreativ\SimpleGifTransitions\Library
 */
class Config
{

    protected $_debug = false;

    protected $_tempDirectory;
    protected $_imageDriver = 'gd';

    protected $_frameImageQuality = 70;

    const IMAGE_DRIVER_GD = 'gd';
    const IMAGE_DRIVER_IMAGICK = 'imagick';

    protected $_allowedDrivers = [
        self::IMAGE_DRIVER_GD,
        self::IMAGE_DRIVER_IMAGICK
    ];


    public function __construct()
    {

    }

    public function setTempDirectory( $tempDirectory )
    {
        if( !is_dir( $tempDirectory ) ){
            throw new \Exception('Temporary directory should already exist');
        }

        $this->_tempDirectory = $tempDirectory;

        return $this;
    }

    public function getTempDirectory()
    {
        return $this->_tempDirectory;
    }

    public function setImageDriver( $driver )
    {
        if( in_array( $driver, $this->_allowedDrivers ) ){
            $this->_imageDriver = $driver;
        }
        return $this;
    }

    public function getImageDriver()
    {
        return $this->_imageDriver;
    }

    public function setFrameImageQuality( $integer )
    {
        if( is_int( $integer ) ){
            $this->_frameImageQuality = $integer;
        }
        return $this;
    }

    public function getFrameImageQuality()
    {
        return $this->_frameImageQuality;
    }

    public function isDebug()
    {
        return (bool) $this->_debug;
    }

    public function setDebug( $bool )
    {
        $this->_debug = (bool) $bool;
        return $this;
    }
}