<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;

class Login extends BaseLogin
{
	public function authenticate(): ?LoginResponse
	{
		try {
			// Raise the limit slightly to reduce unnecessary delays on repeated attempts.
			$this->rateLimit(10);
		} catch (TooManyRequestsException $exception) {
			$this->getRateLimitedNotification($exception)?->send();

			return null;
		}

		$data = $this->form->getState();

		if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
			$this->throwFailureValidationException();
		}

		$user = Filament::auth()->user();

		if (($user instanceof FilamentUser) && (! $user->canAccessPanel(Filament::getCurrentOrDefaultPanel()))) {
			Filament::auth()->logout();

			$this->throwFailureValidationException();
		}

		session()->regenerate();

		session()->flash('login_success_message', 'Selamat datang, Anda berhasil login ke dashboard.');

		return app(LoginResponse::class);
	}

	protected function getAuthenticateFormAction(): Action
	{
		return parent::getAuthenticateFormAction()
			->label('Masuk Dashboard')
			->extraAttributes([
				'wire:loading.attr' => 'disabled',
				'wire:target' => 'authenticate',
				'wire:loading.class' => 'animate-pulse opacity-80',
			]);
	}
}
