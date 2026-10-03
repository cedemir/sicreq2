<?php

namespace App\Models;

use Database\Factories\SituacaoTituloFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'situacao_titulo', key: 'situacao_codigo', timestamps: false)]
#[Fillable(['situacao_descricao'])]
class SituacaoTitulo extends Model
{
    /** @use HasFactory<SituacaoTituloFactory> */
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
     * @return HasMany<Titulo, $this>
     */
    public function titulos(): HasMany
    {
        return $this->hasMany(Titulo::class, 'situacao_codigo', 'situacao_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('situacao_descricao', 'like', "%{$term}%");
            });
        });
    }
}
