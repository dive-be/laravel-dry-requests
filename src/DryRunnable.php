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

    protected function withValidator(Validator $instance)
    {
        $this->withDryValidator($instance);
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

            if ($this->getBehavior()->isFirstFailure()) {
                $instance->stopOnFirstFailure();
            }
        }

        return $instance;
    }

    private function getBehavior(): Validation
    {
        foreach ((new ReflectionMethod($this, 'rules'))->getAttributes(Dry::class) as $attribute) {
            return $attribute->newInstance()->behavior;
        }

        $default = $this->container['config']['dry-requests.validation'];

        if (in_array($header = $this->headers->get(ServiceProvider::HEADER), Validation::toValues())) {
            $default = $header;
        }

        return Validation::from($default);
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
