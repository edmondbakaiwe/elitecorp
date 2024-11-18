<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function inscription(Request $request)
    {
        $utilisateurDonnees = $request->validate([
            'Nom' => ['required', 'string', 'max:55'],
            'Prenom' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'Numero_telephone' => ['required', 'string'],
            'Numero_Whatsapp' => ['nullable', 'string'],
            'Ville' => ['nullable', 'string'],
            'Password' => ['required', 'string', 'min:6', 'max:12', 'confirmed'],
        ]);

        $utilisateur = User::create([
            'Nom' => $utilisateurDonnees['Nom'],
            'Prenom' => $utilisateurDonnees['Prenom'] ?? '',
            'email' => $utilisateurDonnees['email'],
            'Numero_telephone' => $utilisateurDonnees['Numero_telephone'],
            'Numero_Whatsapp' => $utilisateurDonnees['Numero_Whatsapp'] ?? '',
            'Ville' => $utilisateurDonnees['Ville'] ?? '',
            'Password' => bcrypt($utilisateurDonnees['Password']),
        ]);

        return response()->json($utilisateur, 201);
    }

    public function connexion(Request $request)
    {
        $utilisateurDonnees = $request->validate([
            'email' => ['required', 'email'],
            'Password' => ['required', 'string', 'min:6', 'max:12'],
        ]);

        $utilisateur = User::where('email', $utilisateurDonnees['email'])->first();

        if (!$utilisateur || !Hash::check($utilisateurDonnees['Password'], $utilisateur->Password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        $token = $utilisateur->createToken('CLE_SECRETE')->plainTextToken;

        return response()->json([
            'Utilisateur' => $utilisateur,
            'Token' => $token,
        ], 200);
    }
}
