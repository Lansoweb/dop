<?php
namespace Dop;

class Task
{
    private $name;
    private $callback;

    public function __construct($name, callable $callback = null)
    {
        $this->name = $name;
        $this->callback = $callback;
    }

    public function run()
    {
        call_user_func($this->callback);
    }
}
