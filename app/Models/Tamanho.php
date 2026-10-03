<?php

namespace App\Models;

use Database\Factories\TamanhoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'tamanho', key: 'tamanho_codigo', timestamps: false)]
#[Fillable(['tamanho_descricao', 'tamanho_linha', 'tamanho_coluna'])]
class Tamanho extends Model
{
    /** @use HasFactory<TamanhoFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tamanho_linha' => 'float',
            'tamanho_coluna' => 'float',
        ];
    }

    /**
     * @return HasMany<Exemplar, $this>
     */
    public function exemplares(): HasMany
    {
        return $this->hasMany(Exemplar::class, 'tamanho_codigo', 'tamanho_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('tamanho_descricao', 'like', "%{$term}%");
            });
        });
    }
}
