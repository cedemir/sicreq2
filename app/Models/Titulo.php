<?php

namespace App\Models;

use Database\Factories\TituloFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table(name: 'titulo', key: 'titulo_codigo', timestamps: false)]
#[Fillable(['titulo_descricao', 'editora_codigo', 'tipotitulo_codigo', 'situacao_codigo', 'titulo_periodicidade', 'titulo_origem'])]
class Titulo extends Model
{
    /** @use HasFactory<TituloFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'editora_codigo' => 'integer',
            'tipotitulo_codigo' => 'integer',
            'situacao_codigo' => 'integer',
            'titulo_periodicidade' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Editora, $this>
     */
    public function editora(): BelongsTo
    {
        return $this->belongsTo(Editora::class, 'editora_codigo', 'editora_codigo');
    }

    /**
     * @return BelongsTo<TipoTitulo, $this>
     */
    public function tipoTitulo(): BelongsTo
    {
        return $this->belongsTo(TipoTitulo::class, 'tipotitulo_codigo', 'tipotitulo_codigo');
    }

    /**
     * @return BelongsTo<SituacaoTitulo, $this>
     */
    public function situacao(): BelongsTo
    {
        return $this->belongsTo(SituacaoTitulo::class, 'situacao_codigo', 'situacao_codigo');
    }

    /**
     * @return HasMany<Exemplar, $this>
     */
    public function exemplares(): HasMany
    {
        return $this->hasMany(Exemplar::class, 'titulo_codigo', 'titulo_codigo');
    }

    /**
     * @return HasMany<Historia, $this>
     */
    public function historias(): HasMany
    {
        return $this->hasMany(Historia::class, 'titulo_codigo', 'titulo_codigo');
    }

    /**
     * @return HasMany<ArtistaHistoria, $this>
     */
    public function artistaHistorias(): HasMany
    {
        return $this->hasMany(ArtistaHistoria::class, 'titulo_codigo', 'titulo_codigo');
    }

    /**
     * @return HasMany<HistoriaPersonagem, $this>
     */
    public function historiaPersonagens(): HasMany
    {
        return $this->hasMany(HistoriaPersonagem::class, 'titulo_codigo', 'titulo_codigo');
    }

    /**
     * Filter the records by a free text search term.
     */
    #[Scope]
    protected function search(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('titulo_descricao', 'like', "%{$term}%");
            });
        });
    }
}
