<?php

namespace App\Models;

use Database\Factories\HistoriaPersonagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'historiapersonagem', timestamps: false)]
#[Fillable(['titulo_codigo', 'exemplar_codigo', 'historia_codigo', 'personagem_codigo'])]
class HistoriaPersonagem extends Model
{
    /** @use HasFactory<HistoriaPersonagemFactory> */
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
            'personagem_codigo' => 'integer',
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
     * @return BelongsTo<Personagem, $this>
     */
    public function personagem(): BelongsTo
    {
        return $this->belongsTo(Personagem::class, 'personagem_codigo', 'personagem_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->whereRelation('personagem', 'personagem_descricao', 'like', "%{$term}%")
                    ->orWhereRelation('titulo', 'titulo_descricao', 'like', "%{$term}%");
            });
        });
    }
}
