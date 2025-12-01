<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnalyticsResource\Pages;
use App\Models\Ticket;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;

class AnalyticsResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static BackedEnum | string | null $navigationIcon = 'heroicon-o-chart-bar';

    protected static UnitEnum | string | null $navigationGroup = 'Dane';

    protected static ?string $navigationLabel = 'Analizy';

    protected static ?string $modelLabel = 'Analizy';

    protected static ?string $pluralModelLabel = 'Analizy';

    public static function canCreate(): bool
    {
        return false;
    }

    /**
     * @return array<class-string>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\Analytics::route('/'),
        ];
    }
}
