<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Validation\Validator;

trait DryRunnable
{
    public function isDry(): bool
    {
        return $this->boolean($this->container['config']['dry-requests.parameter']);
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
            $rules = $instance->getRules();

            foreach ($rules as &$definitions) {
                if (count($definitions) && reset($definitions) !== 'sometimes') {
                    array_unshift($definitions, 'sometimes');
                }
            }

            $instance->setRules($rules);
            $instance->stopOnFirstFailure();
        }

        return $instance;
    }

    protected function withValidator(Validator $instance)
    {
        $this->withDryValidator($instance);
    }
}
