<?php declare(strict_types=1);

namespace Tests\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreflightRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }
}
