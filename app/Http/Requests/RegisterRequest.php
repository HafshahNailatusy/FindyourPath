<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'email' => Str::lower(trim((string) $this->input('email'))),
            'username' => Str::lower(trim((string) $this->input('username'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'username' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username'],
            'password' => ['required', 'string', 'min:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Semua bidang wajib diisi!',
            'name.max' => 'Nama, email, dan username maksimal 100 karakter!',
            'email.max' => 'Nama, email, dan username maksimal 100 karakter!',
            'username.max' => 'Nama, email, dan username maksimal 100 karakter!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email sudah terdaftar. Silakan login atau gunakan email lain!',
            'username.min' => 'Username minimal 3 karakter (hanya huruf, angka, underscore)!',
            'username.regex' => 'Username minimal 3 karakter (hanya huruf, angka, underscore)!',
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain!',
            'password.min' => 'Password minimal 5 karakter!',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
