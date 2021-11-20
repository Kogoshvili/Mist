<?php

namespace Vitra\Core;

class Container
{
    protected $instances = [];

    /**
     * Add class to instance
     *
     * @param string $class class name
     *
     * @return void
     */
    public function set($class)
    {
        $this->instances[$class] = $class;
    }

    /**
     * Get class instance
     *
     * @param string $class class name
     *
     * @return mixed
     */
    public function get($class)
    {
        if (!isset($this->instances[$class])) {
            $this->set($class);
        }

        return $this->resolve($this->instances[$class]);
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
                    throw new \Exception("Can not resolve class dependency {$parameter->name}");
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}
