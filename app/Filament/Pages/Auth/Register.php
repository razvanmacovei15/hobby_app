<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Pages\Page;

class Register extends \Filament\Auth\Pages\Register
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->components([
                        $this->getFirstNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),

                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->required();
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->required();
    }
}
