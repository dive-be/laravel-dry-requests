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
        if ($this->isDry()) {
            $this->container['events']->dispatch(RequestRanDry::make($this));

            throw SucceededException::make();
        }
    }

    protected function withValidator(Validator $instance)
    {
        if (! $this->isDry()) {
            return;
        }

        $rules = $instance->getRules();

        foreach ($rules as &$definitions) {
            if (count($definitions) && reset($definitions) !== 'sometimes') {
                array_unshift($definitions, 'sometimes');
            }
        }

        $instance->setRules($rules);
        $instance->stopOnFirstFailure();
    }
}
