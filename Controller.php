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
             case 'Modify Task':
                $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
                if ($task_index === NULL || $task_index === FALSE) {
                    $errors[] = 'The task cannot be modified.';
                } else {
                    $task_to_modify = $this->tasks->get($task_index);
                }
                break;
            case 'Save Changes':
                $i = filter_input(INPUT_POST, 'modifiedtaskid', FILTER_VALIDATE_INT);
                $modified_task = filter_input(INPUT_POST, 'modifiedtask');
                if (empty($modified_task)) {
                    $errors[] = 'The modified task cannot be empty.';
                } elseif($i === NULL || $i === FALSE) {
                    $errors[] = 'The task cannot be modified.';        
                } else {
                    $this->tasks->set($i, $modified_task);
                }
                break;
            case 'Cancel Changes':
                $modified_task = '';
                break;
            case 'Promote Task':
                $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
                if ($task_index === NULL || $task_index === FALSE) {
                    $errors[] = 'The task cannot be promoted.';
                } elseif ($task_index == 0) {
                    $errors[] = 'You can\'t promote the first task.';
                } else {
                    // get the values for the two indexes
                    $task_value = $this->tasks->get($task_index);
                    $prior_task_value = $this->tasks->get($task_index-1);

                    // swap the values
                    $this->tasks->set($task_index - 1, $task_value);
                    $this->tasks->set($task_index, $prior_task_value);
                    break;
                }
            case 'Sort Tasks':
                $task_list = $this->tasks->getTaskArray();
                if (count($task_list) > 2){
                    $this->tasks->sort($task_list);
                break; 
                }

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

