<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class ResetPasswordForm extends Form
{
    /**
     * Build the schema for the reset password form.
     *
     * @param \Cake\Form\Schema $schema The schema to modify.
     * @return \Cake\Form\Schema The modified schema.
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('password', 'string')
            ->addField('confirm_password', 'string');
    }

    /**
     * Default validation rules for the reset password form.
     *
     * @param \Cake\Validation\Validator $validator The validator to modify.
     * @return \Cake\Validation\Validator The modified validator.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('password', 'create')
            ->notEmptyString('password')
            ->requirePresence('confirm_password', 'create')
            ->notEmptyString('confirm_password')
            ->sameAs('confirm_password', 'password', 'Passwords do not match');

        return $validator;
    }
}
