<?php

namespace App\Models;

use Database\Factories\HistoriaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'historia', timestamps: false)]
#[Fillable(['titulo_codigo', 'exemplar_codigo', 'historia_numero', 'historia_titulo', 'historia_resumo', 'historia_cor', 'historia_original'])]
class Historia extends Model
{
    /** @use HasFactory<HistoriaFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'titulo_codigo' => 'integer',
            'exemplar_codigo' => 'integer',
            'historia_numero' => 'integer',
            'historia_cor' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Titulo, $this>
     */
    public function titulo(): BelongsTo
    {
        return $this->belongsTo(Titulo::class, 'titulo_codigo', 'titulo_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('historia_titulo', 'like', "%{$term}%")
                    ->orWhere('historia_original', 'like', "%{$term}%");
            });
        });
    }
}
