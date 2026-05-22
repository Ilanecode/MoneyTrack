<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'montant', 'libelle', 'categorie_id',
        'mode_paiement', 'source', 'beneficiaire',
        'description', 'date', 'user_id', 'statut', 'project_id'
    ];

    protected function casts(): array
    {
        return [
            'date'    => 'date',
            'montant' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted()
    {
        static::saved(function ($transaction) {
            if ($transaction->project_id && $transaction->type === 'entrée') {
                $transaction->project->updateStatus();
            }
        });

        static::deleted(function ($transaction) {
            if ($transaction->project_id && $transaction->type === 'entrée') {
                $transaction->project->updateStatus();
            }
        });
    }
}
