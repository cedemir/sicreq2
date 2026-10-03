<?php

namespace App\Models;

use Database\Factories\PersonagemCriadorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'personagem_criador', timestamps: false)]
#[Fillable(['personagem_codigo', 'artista_codigo'])]
class PersonagemCriador extends Model
{
    /** @use HasFactory<PersonagemCriadorFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personagem_codigo' => 'integer',
            'artista_codigo' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Personagem, $this>
     */
    public function personagem(): BelongsTo
    {
        return $this->belongsTo(Personagem::class, 'personagem_codigo', 'personagem_codigo');
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
                $query->whereRelation('personagem', 'personagem_descricao', 'like', "%{$term}%")
                    ->orWhereRelation('artista', 'artista_nome', 'like', "%{$term}%");
            });
        });
    }
}
