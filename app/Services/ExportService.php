<?php

namespace App\Services;

use App\Models\Soutenance;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class ExportService
{
    /**
     * Export CSV générique
     */
    public function exportCSV($data, $filename)
    {
        $output = fopen('php://temp', 'w');
        
        // Ajouter BOM pour UTF-8 (compatibilité Excel)
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
        
        // En-têtes
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]), ';');
        }
        
        // Données
        foreach ($data as $row) {
            fputcsv($output, $row, ';');
        }
        
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);
        
        return Response::make($content, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
        ]);
    }

    /**
     * Export Excel (format CSV avec extension .xls)
     */
    public function exportExcel($data, $filename)
    {
        $output = fopen('php://temp', 'w');
        
        if (!empty($data)) {
            // En-têtes
            fputcsv($output, array_keys($data[0]), "\t");
            // Données
            foreach ($data as $row) {
                fputcsv($output, $row, "\t");
            }
        }
        
        rewind($output);
        $content = stream_get_contents($output);
        fclose($output);
        
        return Response::make($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename={$filename}.xls",
        ]);
    }

    /**
     * Export des soutenances
     */
    public function exportSoutenances($filters = [])
    {
        $query = Soutenance::with(['etudiant', 'directeur', 'salle']);
        
        if (isset($filters['filiere']) && $filters['filiere']) {
            $query->where('filiere', $filters['filiere']);
        }
        
        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['date_debut']) && $filters['date_debut']) {
            $query->where('date', '>=', $filters['date_debut']);
        }
        
        if (isset($filters['date_fin']) && $filters['date_fin']) {
            $query->where('date', '<=', $filters['date_fin']);
        }
        
        $soutenances = $query->get();
        
        $data = $soutenances->map(function ($s) {
            return [
                'Étudiant' => $s->etudiant->name ?? '-',
                'Email' => $s->etudiant->email ?? '-',
                'Titre' => $s->titre,
                'Filière' => $s->filiere,
                'Type' => $s->type,
                'Date' => $s->date,
                'Heure' => $s->heure,
                'Salle' => $s->salle->nom ?? 'Non affectée',
                'Directeur' => $s->directeur->name ?? 'Non défini',
                'Statut' => $s->statut,
            ];
        })->toArray();
        
        return $this->exportCSV($data, 'soutenances_export');
    }

    /**
     * Export des résultats avec notes
     */
    public function exportResultats($filters = [])
    {
        $query = Soutenance::with(['etudiant', 'directeur', 'pv'])
            ->where('statut', 'realisee');
        
        if (isset($filters['filiere']) && $filters['filiere']) {
            $query->where('filiere', $filters['filiere']);
        }
        
        if (isset($filters['type']) && $filters['type']) {
            $query->where('type', $filters['type']);
        }
        
        $soutenances = $query->get();
        
        $data = $soutenances->map(function ($s) {
            $pv = $s->pv;
            return [
                'Étudiant' => $s->etudiant->name ?? '-',
                'Email' => $s->etudiant->email ?? '-',
                'Titre' => $s->titre,
                'Filière' => $s->filiere,
                'Date' => $s->date,
                'Note' => $pv->note ?? '-',
                'Mention' => $pv->mention ?? '-',
                'Observations' => $pv->observations ?? '-',
                'Décision' => ($pv->note ?? 0) >= 10 ? 'Admis' : 'Non admis',
            ];
        })->toArray();
        
        return $this->exportCSV($data, 'resultats_export');
    }

    /**
     * Export des utilisateurs
     */
    public function exportUsers($role = null)
    {
        $query = User::query();
        
        if ($role) {
            $query->where('role', $role);
        }
        
        $users = $query->get();
        
        $data = $users->map(function ($u) {
            return [
                'Nom' => $u->name,
                'Email' => $u->email,
                'Rôle' => $u->role,
                'Statut' => $u->is_active ? 'Actif' : 'Inactif',
                'Date création' => $u->created_at->format('d/m/Y'),
            ];
        })->toArray();
        
        return $this->exportCSV($data, 'utilisateurs_export');
    }

    /**
     * Export des salles
     */
    public function exportSalles()
    {
        $salles = \App\Models\Salle::all();
        
        $data = $salles->map(function ($s) {
            return [
                'Nom' => $s->nom,
                'Capacité' => $s->capacite,
                'Localisation' => $s->localisation ?? '-',
                'Équipements' => $s->equipements ?? '-',
                'Statut' => $s->actif ? 'Active' : 'Inactive',
            ];
        })->toArray();
        
        return $this->exportCSV($data, 'salles_export');
    }

    /**
     * Export JSON
     */
    public function exportJSON($data, $filename)
    {
        return Response::make(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$filename}.json",
        ]);
    }

    /**
     * Export iCal (calendrier)
     */
    public function exportICal($events, $filename)
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//GestSoutenance//FR\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        
        foreach ($events as $event) {
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . uniqid() . "@gest-soutenance\r\n";
            $ical .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
            $ical .= "DTSTART:" . date('Ymd\THis', strtotime($event['date'] . ' ' . ($event['heure'] ?? '09:00'))) . "\r\n";
            $ical .= "DTEND:" . date('Ymd\THis', strtotime($event['date'] . ' ' . ($event['heure'] ?? '09:00') . ' +2 hours')) . "\r\n";
            $ical .= "SUMMARY:" . $this->escapeICal($event['titre']) . "\r\n";
            $ical .= "DESCRIPTION:" . $this->escapeICal($event['description'] ?? 'Soutenance') . "\r\n";
            $ical .= "LOCATION:" . $this->escapeICal($event['location'] ?? 'À définir') . "\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            $ical .= "END:VEVENT\r\n";
        }
        
        $ical .= "END:VCALENDAR\r\n";
        
        return Response::make($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}.ics",
        ]);
    }

    /**
     * Échapper les caractères pour iCal
     */
    private function escapeICal($string)
    {
        return str_replace(['\\', ',', ';', "\n"], ['\\\\', '\,', '\;', '\n'], $string);
    }
}