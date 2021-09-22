<?php

use Node;

class BFS
{
    private $map;
    private $startNode;
    private $endNode;
    private $mapLength;
    private $mapHigh;
    private $visit;
    private $mousesCoordinate;

    public function __construct(
        $map,
        $mapLength,
        $mapHigh,
        Node $startNode,
        Node $endNode,
        $mousesCoordinate)
    {
        $this->map = $map;
        $this->startNode = $startNode;
        $this->endNode = $endNode;

        $this->mapLength = $mapLength;
        $this->mapHigh = $mapHigh;
        $this->mousesCoordinate = $mousesCoordinate;
    }

    private function validatoionVisit(Node $step){

        foreach ($this->mousesCoordinate as $key => $value) {
            if ($step->getX() == $value['x'] && $step->getY() == $value['y']) {
                return false;
            }
        }

        if (
            $step->getX() <0 ||
            $step->getX() > $this->mapLength - 1 ||
            $step->getY() < 0 ||
            $step->getY() > $this->mapHigh - 1
            )
        {
            return false;
        }else{
            if ($this->map[$step->getX()][$step->getY()] != 1) {
                return true;
            }else{
                return false;
            }
        }
    }

    public function BFS(){
        $queue = [];

        $instructions = [
            [0, 1],
            [0, -1],
            [1, 0],
            [-1, 0],
        ];

        array_push($queue, $this->startNode);

        while(count($queue) != 0){
                $lastStepNode = array_shift($queue);

                $this->visit[$lastStepNode->getX()][$lastStepNode->getY()] = 1;

                foreach ($instructions as $instructionKey => $instruction) {
                    $stepNode = new Node($lastStepNode->getX() + $instruction[0], $lastStepNode->getY() + $instruction[1]);

                    if ($stepNode == $this->endNode) {
                        return $lastStepNode->getSetup();
                    }

                    if($this->validatoionVisit($stepNode) &&
                    !isset($this->visit[$stepNode->getX()][$stepNode->getY()])
                    ) {
                        array_push($queue, $stepNode);
                        $stepNode->setStep($lastStepNode->getSetup() +1);
                    }
                }
        }
        return false;
    }
}
?>