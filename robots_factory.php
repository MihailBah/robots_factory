<?php

class FactoryRobot
{
    private $types = [];

    public function addType($type)
    {
        $this->types['create' . get_class($type)] = $type;
    }

    public function __call($name, $args)
    {
        if (isset($this->types[$name])) {
            $robots = [];
            for ($i = 0; $i < $args[0]; $i++) {
                $robot = clone $this->types[$name];
                $robots[] = $robot->create();
            }
            return $robots;
        } else {
            throw new Exception("Method {$name} is not supported.");
        }
    }

}

class Robot1
{
    public $weight;
    public $speed;
    public $height;

    public function __construct()
    {
        $this->create();
    }

    public function create()
    {
        $this->height = rand(2, 40);
        $this->speed = rand(5, 100);
        $this->weight = rand(10, 50);

        return $this;
    }
}

class Robot2
{
    public $weight;
    public $speed;
    public $height;

    public function __construct()
    {
        $this->create();
    }

    public function create()
    {
        $this->height = rand(2, 40);
        $this->speed = rand(5, 100);
        $this->weight = rand(10, 50);

        return $this;
    }
}

class MergeRobot
{
    private $weight;
    private $speed;
    private $height;

    public function addRobot($params)
    {
        $params = is_array($params) ? $params : [$params];
        foreach ($params as $param) {
            $this->setParams($param);
        }
    }

    private function setParams($robot)
    {
        $this->height += $robot->height;
        $this->weight += $robot->weight;
        $this->speed = is_null($this->speed) || $this->speed > $robot->speed ? $robot->speed : $this->speed;
    }

    /**
     * @return mixed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

    public function create()
    {
        return $this;
    }
}

$factory = new FactoryRobot();
// Robot1, Robot2 типи роботів які може створювати фабрика
$factory->addType(new Robot1());
$factory->addType(new Robot2());
/**
 * Результатом роботи метода createRobot1 буде масив з 5 об’єктів класу Robot1
 * Результатом роботи метода createRobot2 буде масив з 2 об’єктів класу Robot2
 */
var_dump($robots1 = $factory->createRobot1(5));
var_dump($robots2 = $factory->createRobot2(2));
$mergeRobot = new MergeRobot();
$mergeRobot->addRobot(new Robot2());
$mergeRobot->addRobot($factory->createRobot2(2));
$factory->addType($mergeRobot);
$res = reset($factory->createMergeRobot(1));
//Результатом роботи методу буде мінімальна швидкість з усіх об’єднаних роботів
echo $res->getSpeed() . PHP_EOL;
// Результатом роботи методу буде сума всіх ваг об’єднаних роботів
echo $res->getWeight() . PHP_EOL;
?>