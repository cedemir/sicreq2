<?php

namespace App\Models;

use Database\Factories\ArmazenagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'armazenagem', key: 'armazenagem_codigo', timestamps: false)]
#[Fillable(['armazenagem_descricao'])]
class Armazenagem extends Model
{
    /** @use HasFactory<ArmazenagemFactory> */
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
     * @return HasMany<Exemplar, $this>
     */
    public function exemplares(): HasMany
    {
        return $this->hasMany(Exemplar::class, 'armazenagem_codigo', 'armazenagem_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('armazenagem_descricao', 'like', "%{$term}%");
            });
        });
    }
}
