<?php

namespace App\Models;

use Database\Factories\PersonagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'personagem', key: 'personagem_codigo', timestamps: false)]
#[Fillable(['personagem_descricao', 'universo_codigo', 'personagem_origem'])]
class Personagem extends Model
{
    /** @use HasFactory<PersonagemFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'universo_codigo' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Universo, $this>
     */
    public function universo(): BelongsTo
    {
        return $this->belongsTo(Universo::class, 'universo_codigo', 'universo_codigo');
    }

    /**
     * @return HasMany<ImagemPersonagem, $this>
     */
    public function imagensPersonagem(): HasMany
    {
        return $this->hasMany(ImagemPersonagem::class, 'personagem_codigo', 'personagem_codigo');
    }

    /**
     * @return HasMany<PersonagemCriador, $this>
     */
    public function personagemCriadores(): HasMany
    {
        return $this->hasMany(PersonagemCriador::class, 'personagem_codigo', 'personagem_codigo');
    }

    /**
     * @return HasMany<HistoriaPersonagem, $this>
     */
    public function historiaPersonagens(): HasMany
    {
        return $this->hasMany(HistoriaPersonagem::class, 'personagem_codigo', 'personagem_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('personagem_descricao', 'like', "%{$term}%");
            });
        });
    }
}
