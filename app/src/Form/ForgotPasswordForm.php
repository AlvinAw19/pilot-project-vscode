<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class ForgotPasswordForm extends Form
{
    /**
     * Build the schema for the reset password form.
     *
     * @param \Cake\Form\Schema $schema The schema to modify.
     * @return \Cake\Form\Schema The modified schema.
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('email', 'string');
    }

    /**
     * Default validation rules for the forgot password form.
     *
     * @param \Cake\Validation\Validator $validator The validator to modify.
     * @return \Cake\Validation\Validator The modified validator.
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        return $validator;
    }
}
