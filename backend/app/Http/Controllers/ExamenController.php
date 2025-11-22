<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamenController extends Controller
{
    public function index()
    {
        $examens = Examen::with(['module', 'salle', 'groupe', 'responsable'])->get();
        return response()->json(['examens' => $examens]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['resp', 'chef'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date_examen' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'type_examen' => 'required|in:ecrit,TP,Rattrapage',
            'id_module' => 'required|exists:modules,id_module',
            'id_salle' => 'required|exists:salles,id_salle',
            'id_groupe' => 'required|exists:groupes,id_groupe'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $examen = Examen::create([
            ...$request->all(),
            'id_responsable' => auth()->id(),
            'etat_validation' => 'En attente'
        ]);

        return response()->json([
            'message' => 'Examen created successfully',
            'examen' => $examen->load(['module', 'salle', 'groupe', 'responsable'])
        ], 201);
    }

    public function show($id)
    {
        $examen = Examen::with(['module', 'salle', 'groupe', 'responsable', 'surveillances.enseignant'])
                       ->findOrFail($id);
        return response()->json(['examen' => $examen]);
    }

    public function update(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);

        if ($examen->id_responsable != auth()->id() && !auth()->user()->hasRole('chef')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date_examen' => 'sometimes|date',
            'heure_debut' => 'sometimes|date_format:H:i',
            'heure_fin' => 'sometimes|date_format:H:i|after:heure_debut',
            'type_examen' => 'sometimes|in:ecrit,TP,Rattrapage',
            'id_module' => 'sometimes|exists:modules,id_module',
            'id_salle' => 'sometimes|exists:salles,id_salle',
            'id_groupe' => 'sometimes|exists:groupes,id_groupe'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $examen->update($request->all());

        return response()->json([
            'message' => 'Examen updated successfully',
            'examen' => $examen->load(['module', 'salle', 'groupe', 'responsable'])
        ]);
    }

    public function destroy($id)
    {
        $examen = Examen::findOrFail($id);

        if ($examen->id_responsable != auth()->id() && !auth()->user()->hasRole('chef')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $examen->delete();

        return response()->json(['message' => 'Examen deleted successfully']);
    }

    public function validateExamen($id)
    {
        $examen = Examen::findOrFail($id);

        if (!auth()->user()->hasRole('chef')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $examen->update(['etat_validation' => 'Validé']);

        return response()->json([
            'message' => 'Examen validated successfully',
            'examen' => $examen
        ]);
    }
}
