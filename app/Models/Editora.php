<?php

namespace App\Models;

use Database\Factories\EditoraFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'editora', key: 'editora_codigo', timestamps: false)]
#[Fillable(['editora_descricao'])]
class Editora extends Model
{
    /** @use HasFactory<EditoraFactory> */
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
        return $this->hasMany(Titulo::class, 'editora_codigo', 'editora_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('editora_descricao', 'like', "%{$term}%");
            });
        });
    }
}
