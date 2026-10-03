<?php

namespace App\Models;

use Database\Factories\ArtistaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Table(name: 'artista', key: 'artista_codigo', timestamps: false)]
#[Fillable(['artista_nome', 'artista_historico', 'artista_foto'])]
class Artista extends Model
{
    /** @use HasFactory<ArtistaFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [

        ];
    }

    /**
     * @return HasMany<PersonagemCriador, $this>
     */
    public function personagemCriadores(): HasMany
    {
        return $this->hasMany(PersonagemCriador::class, 'artista_codigo', 'artista_codigo');
    }

    /**
     * @return HasMany<ArtistaHistoria, $this>
     */
    public function artistaHistorias(): HasMany
    {
        return $this->hasMany(ArtistaHistoria::class, 'artista_codigo', 'artista_codigo');
    }

    /**
     * Get the public URL of the stored image, if any.
     */
    public function imageUrl(): ?string
    {
        return $this->artista_foto ? Storage::disk('public')->url($this->artista_foto) : null;
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('artista_nome', 'like', "%{$term}%");
            });
        });
    }
}
