<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_contact',
        'description',
        'budget',
        'modalite_paiement',
        'deadline',
        'montant_paye',
        'statut',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'montant_paye' => 'decimal:2',
            'deadline' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getResteAPayerAttribute()
    {
        $entrees = $this->transactions()->where('type', 'entrée')->where('statut', 'approuvé')->sum('montant');
        return $this->budget - $entrees;
    }

    public function updateStatus()
    {
        if ($this->reste_a_payer <= 0 && $this->statut !== 'solde') {
            $this->update(['statut' => 'solde']);
        } elseif ($this->reste_a_payer > 0 && $this->statut === 'solde') {
            $this->update(['statut' => 'en_cours']);
        }
    }
}
