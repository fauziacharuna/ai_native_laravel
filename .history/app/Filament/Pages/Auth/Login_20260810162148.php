<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

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

		if (($user instanceof \Filament\Models\Contracts\FilamentUser) && (! $user->canAccessPanel(Filament::getCurrentPanel()))) {
			Filament::auth()->logout();

			$this->throwFailureValidationException();
		}

		session()->regenerate();

		Notification::make()
			->title('Login berhasil')
			->body('Selamat datang, Anda masuk ke dashboard.')
			->success()
			->send();

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
