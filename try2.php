<?php

// ini_set("display_errors",1);
// error_reporting(E_ALL);

class Node{

    //Node position
    private $pos;
    //Node previous to position from
    private $prev;
    //Node distance from the start
    private $g;
    //Node distance from the goal
    private $h;
    //f = g + h
    private $f;
    //size of board
    private $size = 8;

    //Node constructor
    public function __construct($pos,$prev,$g,$h){
        $this->pos = $pos;
        $this->prev = $prev;
        $this->g = $g;
        $this->h = $h;
        $this->f = $g + $h;
    }

    public function copyNode($node){
        return new self($node->pos,$node->prev, $node->g, $node->h, $node->f);
    }

    //Get and Set
    function setPos($pos){
        $this->pos = $pos;
    }

    function setPrev($prev){
        $this->prev = $prev;
    }

    function setG($g){
        $this->g = $g;
    }

    function setH($h){
        $this->h = $h;
    }

    function getPos(){
        return $this->pos;
    }

    function getPrev(){
        return $this->prev;
    }

    function getG(){
        return $this->g;
    }

    function getH(){
        return $this->h;
    }

    function getF(){
        return $this->f;
    }

    function getNodeI($list,$next){
      
        for($i = 0; $i < count($list); $i++){
            if($list[$i]->getPos() == $next){
                echo "a";
                return $i;
            }
            $i++;
        }

    }

    //Check if this Node is the goal
    function checkGoal(){
        if($this->h == 0)
            return TRUE;
        return FALSE;
    }
    //Node is in the close or open array
    function isCNO($list,$check){
        foreach($list as $elem){
            if($elem->getPos() == $check)
                return TRUE;
        }
        return FALSE;
    }

    //Check if next position its possible on the board 
    function isValid($pos){
        if($pos[0] < $this->size && $pos[1] < $this->size && $pos[0] > 0 && $pos[1] > 0)
            return TRUE;
        return FALSE;
    }
    
    //Node distance from the start position
    function setHValue($start,$end){
        return ((double)sqrt(($start[0] - $end[0]) * ($start[0] - $end[0]) + ($start[1] - $end[1]) * ($start[1] - $end[1])));
    }

    //Search the sortest why from one position to the outer
    function findMinPath($start,$end){

        
        //Array of object to evaluated
        $open = array();
        $open[0] = new Node($start,$start,0,$this->setHValue($start,$end));
        // print_r($open);
        //Open array counter
        $countO = 1;
        
        //Array of object that are already evaluated
        $close = array();
        //Close array counter
        $countC = 0;
        
        //Current object evaluated
        $temp = array();
       

        //Knight possible moves on x and y
        $x = array(2, 1, -1, -2, -2, -1, 1, 2);
        $y = array(1, 2, 2, 1, -1, -2, -2, -1);


        //to change
        $t = 1;
        while($t){
            
            //Minimum f object
            $minF = INF;
            //Index of the minimum f object
            $index;

            //All path array 
            $path = array(); 

            //Loop to search the object with the lowest f value
            for($i = 0; $i < $countO; $i++){
                if($open[$i]->getF() < $minF){
                    $minF = $open[$i]->getF();
                    $index = $i;
                }
            }

            //Temp get the obkect with the lowest f value
            $temp = $open[$index];
            // print_r($temp);
            //Index get out of the open queue and in to the close queue
            unset($open[$index]);
            $open = array_values($open);
            // print_r($open);
            $countO--;
            $close[$countC] = $temp;
            $countC++;
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            // echo "33333333333333333333333333333333";
            // echo $temp->getH();

            // print_r($close);
            //Check if this Node is the goal and retrun the path
            if($temp->getH() == 0){
                echo "in";
                while($temp->getG() != 0){
                    array_push($path,$temp->getPos());
                    $temp = $temp->getPrev();
                }
                array_push($path,$temp->getPos());
                // print_r($path);
                return array_reverse($path);
            }

            $next = $temp->getPos();
            // $next = $next['pos'];
            // print_r($next);
            //Check all possible moves
            for($i = 0; $i < 8; $i++){

                $next[0] += $x[$i];
                $next[1] += $y[$i];

                //Check if next possible move are valid 
                if($this->isValid($next))
                    continue;
                
                //Check if next possible move alrady on the close queue
                if($this->isCNO($close,$next))
                    continue;
                
                //Check if next possible move not on the open queue
                if(!$this->isCNO($open,$next)){
                    $newPos = array(($x[$i]+$next[0]),($y[$i]+$next[1]));
                    // echo "<br>";
                    // echo "<br>";
                    // print_r($newPos);
                    $open[$countO++] = new Node(
                        $newPos,$temp,($temp->getG() + 2.236),$this->setHValue($newPos,$end));  
                }
                // Check if there is a shorter way to the next node 
                elseif(($temp->getG() + 2.236) < $open[$temp->getNodeI($open,$next)]->getG()){
                    $open[$temp->getNodeI($open,$next)]->setG($temp->getG() + 2.236);
                    $open[$temp->getNodeI($open,$next)]->setPrev($temp);
                }
            }
        }
    }
}


$one = array(1,1);
$tow = array(3,5);
$t = new Node(1,5,1,1);
$t->findMinPath($one,$tow);
// print_r($a);


// $tree = array(2,3);
// $d = new Node(0,0,0,0);
// print_r($d);
// $t = array(new Node($one,$one,0,$d->setHValue($one,$tow)), new Node($one,$tree,2.236,$d->setHValue($tree,$tow)),new Node($one,$tree,2.236,$d->setHValue($tree,$tow)));
// $c =  new Node($one,$tree,2.236,$d->setHValue($tree,$tow));
// echo "<br>";
// echo "<br>";
// if($t[0]->isCNO($t,$c))
//     echo "yes";
// else 
//     echo "no";

//     $z = $t[1];
    
//     print_r($t);
//     echo "<br>";
// echo "<br>";
// unset($t[1]);
// $t = array_values($t);

// print_r($t);