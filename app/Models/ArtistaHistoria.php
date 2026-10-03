<?php

namespace App\Models;

use Database\Factories\ArtistaHistoriaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'artista_historia', timestamps: false)]
#[Fillable(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'artista_codigo', 'participa_artistahistoria'])]
class ArtistaHistoria extends Model
{
    /** @use HasFactory<ArtistaHistoriaFactory> */
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
            'historia_codigo' => 'integer',
            'artista_codigo' => 'integer',
            'participa_artistahistoria' => 'integer',
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
     * @return BelongsTo<Artista, $this>
     */
    public function artista(): BelongsTo
    {
        return $this->belongsTo(Artista::class, 'artista_codigo', 'artista_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->whereRelation('artista', 'artista_nome', 'like', "%{$term}%")
                    ->orWhereRelation('titulo', 'titulo_descricao', 'like', "%{$term}%");
            });
        });
    }
}
