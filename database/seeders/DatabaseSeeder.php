<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Place, Classe, Student};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer l'administrateur
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer des formateurs
        $formateur1 = User::create([
            'name' => 'Jean Dupont',
            'email' => 'jean@test.com',
            'password' => Hash::make('password'),
            'role' => 'formateur',
        ]);

        $formateur2 = User::create([
            'name' => 'Marie Martin',
            'email' => 'marie@test.com',
            'password' => Hash::make('password'),
            'role' => 'formateur',
        ]);

        // Créer les lieux
        $place1 = Place::create([
            'name' => 'Perigny',
        ]);

        $place2 = Place::create([
            'name' => 'Chatelaillon',
        ]);

        // Créer des classes
        $classe1 = Classe::create([
            'name' => 'BTS SIO SLAM 2024-2025',
            'description' => 'Promotion 2024-2025 - Spécialité SLAM',
            'formateur_id' => $formateur1->id,
            'place_id' => $place1->id,
        ]);

        $classe2 = Classe::create([
            'name' => 'BTS SIO SISR 2024-2025',
            'description' => 'Promotion 2024-2025 - Spécialité SISR',
            'formateur_id' => $formateur2->id,
            'place_id' => $place1->id,
        ]);

        $classe3 = Classe::create([
            'name' => 'Licence Pro DevOps 2024-2025',
            'description' => 'Licence professionnelle DevOps',
            'formateur_id' => $formateur1->id,
            'place_id' => $place2->id,
        ]);

        // Créer des étudiants
        $students = [
            ['firstname' => 'Pierre', 'lastname' => 'Durand', 'email' => 'pierre.durand@test.com', 'birthdate' => '2005-03-15', 'classe_id' => $classe1->id],
            ['firstname' => 'Sophie', 'lastname' => 'Bernard', 'email' => 'sophie.bernard@test.com', 'birthdate' => '2004-07-22', 'classe_id' => $classe1->id],
            ['firstname' => 'Lucas', 'lastname' => 'Petit', 'email' => 'lucas.petit@test.com', 'birthdate' => '2003-11-08', 'classe_id' => $classe2->id],
            ['firstname' => 'Emma', 'lastname' => 'Roux', 'email' => 'emma.roux@test.com', 'birthdate' => '2005-01-30', 'classe_id' => $classe2->id],
            ['firstname' => 'Hugo', 'lastname' => 'Moreau', 'email' => 'hugo.moreau@test.com', 'birthdate' => '2004-09-12', 'classe_id' => $classe3->id],
            ['firstname' => 'Léa', 'lastname' => 'Simon', 'email' => 'lea.simon@test.com', 'birthdate' => '2003-05-25', 'classe_id' => $classe3->id],
        ];

        foreach ($students as $student) {
            Student::create($student);
        }

        $this->command->info('✅ Base de données peuplée avec succès !');
        $this->command->info('📧 Admin : admin@test.com / password');
        $this->command->info('📧 Formateur 1 : jean@test.com / password');
        $this->command->info('📧 Formateur 2 : marie@test.com / password');
    }
}
