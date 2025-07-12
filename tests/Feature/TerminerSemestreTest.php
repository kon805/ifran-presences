<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classe;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TerminerSemestreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_peut_terminer_semestre()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $classe = Classe::factory()->create([
            'semestre' => '1',
            'statut' => 'en_cours'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.classes.terminer-semestre', $classe->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'statut' => 'termine'
        ]);
    }

    public function test_coordinateur_peut_terminer_semestre_de_sa_classe()
    {
        $coordinateur = User::factory()->create(['role' => 'coordinateur']);
        $classe = Classe::factory()->create([
            'semestre' => '1',
            'statut' => 'en_cours',
            'coordinateur_id' => $coordinateur->id
        ]);

        $response = $this->actingAs($coordinateur)
            ->post(route('coordinateur.classes.terminer-semestre', $classe->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'statut' => 'termine'
        ]);
    }

    public function test_coordinateur_ne_peut_pas_terminer_semestre_autre_classe()
    {
        $coordinateur = User::factory()->create(['role' => 'coordinateur']);
        $autreCoordinateur = User::factory()->create(['role' => 'coordinateur']);
        $classe = Classe::factory()->create([
            'semestre' => '1',
            'statut' => 'en_cours',
            'coordinateur_id' => $autreCoordinateur->id
        ]);

        $response = $this->actingAs($coordinateur)
            ->post(route('coordinateur.classes.terminer-semestre', $classe->id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'statut' => 'en_cours'
        ]);
    }

    public function test_ne_peut_pas_terminer_semestre_2()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $classe = Classe::factory()->create([
            'semestre' => '2',
            'statut' => 'en_cours'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.classes.terminer-semestre', $classe->id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('classes', [
            'id' => $classe->id,
            'statut' => 'en_cours'
        ]);
    }

    public function test_etudiants_migres_vers_semestre_2()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $classe1 = Classe::factory()->create([
            'annee_academique' => '2025-2026',
            'semestre' => '1',
            'statut' => 'en_cours'
        ]);

        $etudiant1 = User::factory()->create(['role' => 'etudiant']);
        $etudiant2 = User::factory()->create(['role' => 'etudiant']);

        $classe1->etudiants()->attach([$etudiant1->id, $etudiant2->id]);

        $classe2 = Classe::factory()->create([
            'annee_academique' => '2025-2026',
            'semestre' => '2',
            'statut' => 'en_cours'
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.classes.terminer-semestre', $classe1->id));

        $response->assertRedirect();

        $this->assertDatabaseHas('classe_user', [
            'classe_id' => $classe2->id,
            'user_id' => $etudiant1->id
        ]);

        $this->assertDatabaseHas('classe_user', [
            'classe_id' => $classe2->id,
            'user_id' => $etudiant2->id
        ]);
    }
}
