<?php declare(strict_types=1);

namespace Dive\DryRequests;

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

    protected function prepareForValidation()
    {
        if ($this->isDry()) {
            $this->stopOnFirstFailure = true;
        }
    }
}
