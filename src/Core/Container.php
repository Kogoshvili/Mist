<?php

namespace Mist\Core;

class Container
{
    /**
     * The container's instance.
     *
     * @var static
     */
    protected static $instance;

    /**
     * Array of initialized singletons.
     *
     * @var array
     */
    protected static $singletons = [];

    /**
     * Binds of interfaces and concrete classes.
     *
     * @var array
     */
    protected $binds = [];

    public function __construct()
    {
        $config = include_once CONFIG . 'container.php';
        $this->binds = $config['binds'];
        self::$singletons = $config['singletons'];
        self::$instance = $this;
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Add class to instance
     *
     * @param string $class     class name
     * @param string $interface interface name
     *
     * @return void
     */
    public function bind($class, $interface = null)
    {
        $this->binds[$interface ?? $class] = $class;
    }

    /**
     * Make new instance
     *
     * @param string $class class name
     *
     * @return mixed
     */
    public function make($class)
    {
        $this->bind($class);
        return $this->resolve($class);
    }

    /**
     * Get instance is exists or create new
     *
     * @param string $class class name
     *
     * @return mixed
     */
    public function get($class)
    {
        if (in_array($class, self::$singletons)) {
            return $this->singleton($class);
        }

        return $this->make($class);
    }

    /**
     * Get singleton instance
     *
     * @param string $class class name
     *
     * @return mixed
     */
    public function singleton($class)
    {
        $this->bind($class);
        return self::$singletons[$class] ??= $this->resolve($class);
    }

    /**
     * Call method and resolve its dependencies
     *
     * @param string $class  class name
     * @param string $method method name
     * @param array  $args   method arguments
     *
     * @return mixed
     */
    public function call($class, $method, $args = [])
    {
        $reflector = new \ReflectionMethod($class, $method);
        $parameters = $reflector->getParameters();
        $classInstance = $this->get($class);

        if ($parameters) {
            $dependencies = $this->getDependencies($parameters);
            return $classInstance->$method(...$dependencies, ...$args);
        }

        return $classInstance->$method(...$args);
    }

    /**
     * Resolve class
     *
     * @param string $class class name
     *
     * @return mixed
     */
    public function resolve($class)
    {
        if ($class instanceof \Closure) {
            return $class($this);
        }

        $reflector = new \ReflectionClass($class);
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable");
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Get class dependencies
     *
     * @param array $parameters parameters
     *
     * @return array
     */
    public function getDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    //throw new \Exception("Can not resolve class dependency {$parameter->name}");
                    continue;
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}
