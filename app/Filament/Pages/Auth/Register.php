<?php

namespace App\Filament\Pages\Auth;

use App\Services\IUserWorkspaceService;
use Filament\Auth\Http\Responses\RegistrationResponse;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Auth\Events\Registered;
use Filament\Pages\Page;
use Filament\Auth\Pages\Register as BaseRegister;


class Register extends BaseRegister
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                $this->getFirstNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
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

    protected function getNameFormComponent(): Component
    {
        // Return null to hide the default name field since we use first_name and last_name
        return TextInput::make('name')->hidden();
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();
        // Use your custom service to create user with workspace
        $userWorkspaceService = app(IUserWorkspaceService::class);
        $user = $userWorkspaceService->registerUserWithDefaultWorkspace($data);

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }
}
