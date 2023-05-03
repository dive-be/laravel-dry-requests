<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Illuminate\Validation\Validator;
use ReflectionMethod;

/**
 * @method bool isDry()
 *
 * @mixin \Illuminate\Foundation\Http\FormRequest
 */
trait DryRunnable
{
    protected function passedValidation(): void
    {
        $this->stopWhenDry();
    }

    protected function stopWhenDry(): void
    {
        if ($this->isDry()) {
            $this->stopDryRequest();
        }
    }

    protected function withDryValidator(Validator $instance): Validator
    {
        return $this->isDry()
            ? Dryer::make($this)->onlyPresent($instance)->setBehavior($instance, $this->getBehavior())
            : $instance;
    }

    protected function withValidator(Validator $instance): void
    {
        $this->withDryValidator($instance);
    }

    private function getBehavior(): ?Validation
    {
        foreach ((new ReflectionMethod($this, 'rules'))->getAttributes(Dry::class) as $attribute) {
            return $attribute->newInstance()->behavior;
        }

        return null;
    }
}
