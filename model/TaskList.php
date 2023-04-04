<?php
class TaskList
{
    private $tasks;

    public function __construct() {
        $this->tasks = filter_input(INPUT_POST, 'tasklist', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if ($this->tasks === NULL) {
            $this->tasks = array();
            $this->tasks[] = 'Read Chapter 11';
            $this->tasks[] = 'Complete Lab 5';
            $this->tasks[] = 'Take a break';
        }
    }
    
    public function getTaskArray() {
        return $this->tasks;
    }
    
    public function add($new_task) {
        $this->tasks[] = $new_task;
    }
    
    public function remove($index) {
        unset($this->tasks[$index]);
        $this->tasks = array_values($this->tasks);
    }
}

