<?php
// Service de génération PDF

class PdfService {
    
    /**
     * Génère une convocation PDF
     */
    public function generateConvocation($soutenance, $etudiant, $jury) {
        // Nécessite une bibliothèque comme dompdf ou FPDF
        // Exemple avec dompdf (à installer via composer)
        
        $html = $this->getConvocationTemplate($soutenance, $etudiant, $jury);
        
        // Avec dompdf
        // $dompdf = new Dompdf\Dompdf();
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->render();
        // return $dompdf->output();
        
        // Simulé pour l'instant
        return $html;
    }
    
    /**
     * Template de la convocation
     */
    private function getConvocationTemplate($soutenance, $etudiant, $jury) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Convocation à la soutenance</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #667eea; }
                .content { margin: 20px 0; }
                .info { margin: 10px 0; }
                .signatures { margin-top: 50px; display: flex; justify-content: space-between; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Université de Thiès / UNCHK</h1>
                <h2>Convocation à la soutenance</h2>
            </div>
            <div class='content'>
                <p>Madame, Monsieur,</p>
                <p>Nous vous prions de bien vouloir assister à la soutenance de :</p>
                <div class='info'>
                    <strong>Étudiant :</strong> {$etudiant['prenom']} {$etudiant['nom']}<br>
                    <strong>Titre :</strong> {$soutenance['titre']}<br>
                    <strong>Filière :</strong> {$soutenance['filiere']}<br>
                    <strong>Type :</strong> {$soutenance['type']}<br>
                    <strong>Date :</strong> " . date('d/m/Y', strtotime($soutenance['date'])) . "<br>
                    <strong>Heure :</strong> " . date('H:i', strtotime($soutenance['heure'])) . "<br>
                    <strong>Salle :</strong> {$soutenance['salle_nom']} ({$soutenance['localisation']})<br>
                </div>
                <p>Composition du jury :</p>
                <ul>";
        
        foreach ($jury as $membre) {
            $html .= "<li><strong>" . ucfirst($membre['role']) . " :</strong> {$membre['prenom']} {$membre['nom']}</li>";
        }
        
        $html .= "
                </ul>
            </div>
            <div class='signatures'>
                <div>Le responsable pédagogique</div>
                <div>Le chef de département</div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Génère un PV PDF
     */
    public function generatePV($soutenance, $etudiant, $jury, $pv) {
        $html = $this->getPVTemplate($soutenance, $etudiant, $jury, $pv);
        
        // Avec dompdf
        // $dompdf = new Dompdf\Dompdf();
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        // $dompdf->render();
        // return $dompdf->output();
        
        return $html;
    }
    
    /**
     * Template du PV
     */
    private function getPVTemplate($soutenance, $etudiant, $jury, $pv) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Procès-verbal de soutenance</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .header h1 { color: #667eea; }
                .content { margin: 20px 0; }
                .result { margin: 20px 0; padding: 15px; background: #f0f0f0; }
                .signatures { margin-top: 50px; display: flex; justify-content: space-between; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>Université de Thiès / UNCHK</h1>
                <h2>Procès-verbal de soutenance</h2>
            </div>
            <div class='content'>
                <p>Le " . date('d/m/Y', strtotime($soutenance['date'])) . " à " . date('H:i', strtotime($soutenance['heure'])) . ",</p>
                <p>a eu lieu la soutenance de :</p>
                <div class='info'>
                    <strong>Étudiant :</strong> {$etudiant['prenom']} {$etudiant['nom']}<br>
                    <strong>Titre :</strong> {$soutenance['titre']}<br>
                    <strong>Filière :</strong> {$soutenance['filiere']}<br>
                    <strong>Type :</strong> {$soutenance['type']}<br>
                </div>
                
                <div class='result'>
                    <h3>Résultats</h3>
                    <strong>Note :</strong> {$pv['note']}/20<br>
                    <strong>Mention :</strong> {$pv['mention']}<br>
                    <strong>Observations :</strong> " . ($pv['observations'] ?? '-') . "<br>
                    <strong>Décision :</strong> " . ($pv['note'] >= 10 ? 'Admis(e)' : 'Non admis(e)') . "
                </div>
                
                <p>Composition du jury :</p>
                <ul>";
        
        foreach ($jury as $membre) {
            $html .= "<li><strong>" . ucfirst($membre['role']) . " :</strong> {$membre['prenom']} {$membre['nom']}</li>";
        }
        
        $html .= "
                </ul>
            </div>
            <div class='signatures'>";
        
        foreach ($jury as $membre) {
            $html .= "<div>Signature: {$membre['prenom']} {$membre['nom']}</div>";
        }
        
        $html .= "
            </div>
        </body>
        </html>";
    }
}
?>