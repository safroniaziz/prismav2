<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */


    'email' => 'Field :attribute harus berupa email address.',
    'file' => 'Field :attribute harus berupa file.',

    'max' => [
        'numeric' => 'Field :attribute tidak boleh lebih dari :max.',
        'file' => 'Field :attribute tidak boleh lebih dari :max kilobyte.',
    ],
    'mimes' => 'Field :attribute harus berisi file yang bertipe: :values.',
    'min' => [
        'numeric' => 'Field :attribute harus berisi minimal :min.',
        'file' => 'Field :attribute harus berisi :min kilobyte.',
    ],
    'numeric' => 'Field :attribute harus berupa angka.',
    'required' => 'Field :attribute harus diisi.',
   

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
