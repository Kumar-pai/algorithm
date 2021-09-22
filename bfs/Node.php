<?php

class Node{
    private $x;
    private $y;
    private $step;

    function __construct($x,$y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(){
        return $this->x;
    }

    public function getY(){
        return $this->y;
    }

    public function getSetup(){
        return $this->step;
    }

    public function setStep($lastStep){
        $this->step = $lastStep;
    }
}
?>