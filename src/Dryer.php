<?php declare(strict_types=1);

namespace Dive\DryRequests;

use Dive\Utils\Makeable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Validator;

/**
 * @internal
 */
final class Dryer
{
    use Makeable;

    private function __construct(
        private Request $request,
    ) {}

    public function setBehavior(Validator $validator, ?Validation $behavior = null): Validator
    {
        return $validator->stopOnFirstFailure($this->getBehavior($behavior)->isFirstFailure());
    }

    public function onlyPresent(Validator $validator): self
    {
        $rules = $validator->getRules();

        foreach ($rules as &$definitions) {
            if (count($definitions) && reset($definitions) !== 'sometimes') {
                array_unshift($definitions, 'sometimes');
            }
        }

        $validator->setRules($rules);

        return $this;
    }

    private function getBehavior(?Validation $behavior): Validation
    {
        if ($behavior instanceof Validation) {
            return $behavior;
        }

        $default = Config::get('dry-requests.validation');

        if (in_array($header = $this->request->headers->get(ServiceProvider::HEADER), Validation::toValues())) {
            $default = $header;
        }

        return Validation::from($default);
    }
}
