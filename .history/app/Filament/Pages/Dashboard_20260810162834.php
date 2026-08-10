<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;



use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Notifications\Notification;

class Dashboard extends BaseDashboard
{

    protected static ?string $title = 'Beranda';
     protected static ?string $navigationLabel = 'Beranda';

    public function mount(): void
    {
        $message = session()->pull('login_success_message');

        if ($message) {
            Notification::make()
                ->title('Login berhasil')
                ->body($message)
                ->success()
                ->send();
        }
    }

     public function panel(Panel $panel): Panel{
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('Mall Pelayanan Publik')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ]);

     }
    public function getHeading(): string
    {
        
        return 'Dashboard MPP Kota Bontang';
    }
}
