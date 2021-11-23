<?php

namespace Mist\Core;

class Container
{
    /**
     * The container's instance.
     *
     * @var \Mist\Core\Container
     */
    protected static $instance;

    /**
     * Binds of interfaces and concrete classes.
     *
     * @var array
     */
    protected $binds = [];

    /**
     * Array of classes that should be singletons
     *
     * @var array
     */
    protected $singletons = [];

    /**
     * Array of initialized singletons.
     *
     * @var array
     */
    protected $singletonRegistry = [];

    public function __construct()
    {
        $config = include_once CONFIG . 'container.php';
        $this->binds = $config['binds'];
        $this->singletons = $config['singletons'];
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
        if (in_array($class, $this->singletons)) {
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
        return $this->singletonRegistry[$class] ??= $this->resolve($class);
    }

    /**
     * Call method and resolve its dependencies
     *
     * @param string $class  class name
     * @param string $method method name
     * @param array  $args   method arguments (key value pairs)
     *
     * @return mixed
     */
    public function call($class, $method, $args = [])
    {
        $classInstance = $this->get($class);
        $reflector = new \ReflectionMethod($class, $method);
        $parameters = $reflector->getParameters();

        if ($parameters) {
            $dependencies = $this->getDependencies($parameters, $args);
            return $classInstance->$method(...$dependencies);
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
    public function getDependencies($parameters, $args = [])
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter->name, $args)) {
                $dependencies[] = $args[$parameter->name];
            } else {
                $dependency = $parameter->getClass();
                if ($dependency === null) {
                    if ($parameter->isDefaultValueAvailable()) {
                        $dependencies[] = $parameter->getDefaultValue();
                    } else {
                        throw new \Exception("Can not resolve class dependency {$parameter->name}");
                    }
                } else {
                    $dependencies[] = $this->get($dependency->name);
                }
            }
        }

        return $dependencies;
    }
}
