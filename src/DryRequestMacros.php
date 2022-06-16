<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Closure;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

/**
 * @internal
 */
final class DryRequestMacros
{
    public function isDry(): Closure
    {
        return function (): bool {
            /** @var \Illuminate\Http\Request $this */
            return $this->headers->has(ServiceProvider::HEADER);
        };
    }

    public function stopDryRequest(): Closure
    {
        return function (): never {
            /** @var \Illuminate\Http\Request $this */
            Event::dispatch(RequestRanDry::make($this));

            throw SucceededException::make();
        };
    }

    public function validate(): Closure
    {
        return function (array $rules, array $messages = [], array $customAttributes = []): array {
            /** @var \Illuminate\Http\Request $this */
            $validator = Validator::make($this->all(), $rules, $messages, $customAttributes);

            if (! $this->isDry()) {
                return $validator->validate();
            }

            Dryer::make($this)
                ->onlyPresent($validator)
                ->setBehavior($validator)
                ->validate();

            $this->stopDryRequest();
        };
    }
}
