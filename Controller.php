<?php
require_once './model/TaskList.php';

class Controller
{
    private $action;
    private $tasks;
    private $errors;
    
    public function __construct() {
        $this->action = $this->getAction();
        $this->tasks = new TaskList();
        $this->errors = array();
    }
    
    public function invoke() {
        switch($this->action) {
            case 'Add Task':
                $new_task = filter_input(INPUT_POST, 'newtask', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if (empty($new_task)) {
                    $this->errors[] = 'The new task cannot be empty.';
                } else {
                    $this->tasks->add($new_task);
                }
                break;
            case 'Delete Task':
                $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
                if ($task_index === NULL || $task_index === FALSE) {
                    $this->errors[] = 'The task cannot be deleted.';
                } else {
                    $this->tasks->remove($task_index);
                }
                break;
        }
        $errors = $this->errors;
        $task_list = $this->tasks->getTaskArray();
        include './view/tasklist_page.php';
    }
    
    // get action from POST array
    private function getAction() {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($action === NULL) {
            $action = '';
        }
        return $action;
    }
}

