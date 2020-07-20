<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class User extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $picture = 'nullable';

        if ($this->request->get('picture', null)) {
            $picture = 'mimes:' . config('filesystems.mimes') . '|between:0,' . config('filesystems.max_size') * 1024;
        }

        // Check if store or update
        if ($this->getMethod() == 'PATCH') {
            $id = is_numeric($this->user) ? $this->user : $this->user->getAttribute('id');
            $required = '';
        } else {
            $id = null;
            $required = 'required|';
        }

        /**
         * 2020.07.17 paulus:
         * 當沒有 Read.Auth.Roles 但具有 Read.Auth.Profile 和 Read.Auth.Users 的用戶 edit Profile 時，
         * 他可以編輯 Profile，但是在 Save 時出現 non-reported error 422。此 error 是由於 'roles' 是 required 而引起的。
         * 您可以授予 Read.Auth.Roles 權限，不過我就選擇取消 'required'。
         * 'roles' => 'required' 改為 'roles' => $required
         */
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'password' => $required . 'confirmed',
            'companies' => 'required',
            'roles' => $required,   // 'required'
            'picture' => $picture,
        ];
    }
}
