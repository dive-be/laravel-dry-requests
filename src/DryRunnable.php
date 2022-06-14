<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Validation\Validator;
use ReflectionMethod;

/**
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
trait DryRunnable
{
    public function isDry(): bool
    {
        return $this->headers->has(ServiceProvider::HEADER);
    }

    protected function passedValidation()
    {
        $this->stopWhenDry();
    }

    protected function stopWhenDry()
    {
        if ($this->isDry()) {
            $this->container['events']->dispatch(RequestRanDry::make($this));

            throw SucceededException::make();
        }
    }

    protected function withDryValidator(Validator $instance): Validator
    {
        if ($this->isDry()) {
            $this->validateWhenPresent($instance);

            if ($this->getBehavior()->isStopOnFirstFailure()) {
                $instance->stopOnFirstFailure();
            }
        }

        return $instance;
    }

    protected function withValidator(Validator $instance)
    {
        $this->withDryValidator($instance);
    }

    private function getBehavior(): Validation
    {
        $behavior = Validation::StopOnFirstFailure;

        foreach ((new ReflectionMethod($this, 'rules'))->getAttributes(Dry::class) as $attribute) {
            $behavior = $attribute->newInstance()->behavior;
        }

        return $behavior;
    }

    private function validateWhenPresent(Validator $instance)
    {
        $rules = $instance->getRules();

        foreach ($rules as &$definitions) {
            if (count($definitions) && reset($definitions) !== 'sometimes') {
                array_unshift($definitions, 'sometimes');
            }
        }

        $instance->setRules($rules);
    }
}
