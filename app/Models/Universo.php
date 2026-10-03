<?php

namespace App\Models;

use Database\Factories\UniversoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'universo', key: 'universo_codigo', timestamps: false)]
#[Fillable(['universo_descricao'])]
class Universo extends Model
{
    /** @use HasFactory<UniversoFactory> */
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
     * @return HasMany<Personagem, $this>
     */
    public function personagens(): HasMany
    {
        return $this->hasMany(Personagem::class, 'universo_codigo', 'universo_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('universo_descricao', 'like', "%{$term}%");
            });
        });
    }
}
