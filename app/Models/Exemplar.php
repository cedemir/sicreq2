<?php

namespace App\Models;

use Database\Factories\ExemplarFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Table(name: 'exemplar', timestamps: false)]
#[Fillable(['titulo_codigo', 'exemplar_codigo', 'exemplar_subtitulo', 'exemplar_capa', 'exemplar_data', 'armazenagem_codigo', 'tamanho_codigo', 'exemplar_situacao', 'exemplar_numeropagina'])]
class Exemplar extends Model
{
    /** @use HasFactory<ExemplarFactory> */
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
            'exemplar_data' => 'datetime',
            'armazenagem_codigo' => 'integer',
            'tamanho_codigo' => 'integer',
            'exemplar_situacao' => 'integer',
            'exemplar_numeropagina' => 'integer',
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
     * @return BelongsTo<Armazenagem, $this>
     */
    public function armazenagem(): BelongsTo
    {
        return $this->belongsTo(Armazenagem::class, 'armazenagem_codigo', 'armazenagem_codigo');
    }

    /**
     * @return BelongsTo<Tamanho, $this>
     */
    public function tamanho(): BelongsTo
    {
        return $this->belongsTo(Tamanho::class, 'tamanho_codigo', 'tamanho_codigo');
    }

    /**
     * Get the public URL of the stored image, if any.
     */
    public function imageUrl(): ?string
    {
        return $this->exemplar_capa ? Storage::disk('public')->url($this->exemplar_capa) : null;
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('exemplar_subtitulo', 'like', "%{$term}%")
                    ->orWhereRelation('titulo', 'titulo_descricao', 'like', "%{$term}%");
            });
        });
    }
}
