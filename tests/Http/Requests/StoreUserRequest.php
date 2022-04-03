<?php declare(strict_types=1);

namespace Tests\Http\Requests;

use Dive\DryRequests\DryRunnable;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $email
 * @property string $name
 */
class StoreUserRequest extends FormRequest
{
    use DryRunnable;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'nickname' => ['nullable', 'string', 'min:2', 'max:255']
        ];
    }
}
