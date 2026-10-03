<?php

namespace App\Models;

use Database\Factories\ImagemPersonagemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'imagempersonagem', timestamps: false)]
#[Fillable(['personagem_codigo', 'imagem_codigo', 'imagem_descricao'])]
class ImagemPersonagem extends Model
{
    /** @use HasFactory<ImagemPersonagemFactory> */
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
            'imagem_codigo' => 'integer',
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
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('imagem_descricao', 'like', "%{$term}%")
                    ->orWhereRelation('personagem', 'personagem_descricao', 'like', "%{$term}%");
            });
        });
    }
}
