<?php

use App\Models\ScanAlerts;
use Illuminate\Support\Facades\DB;

function generateHealthData($scan_id)
{
    $result = DB::table('scan_alerts')
        ->join('owasp_zap_core_values', 'scan_alerts.alertRef', '=', 'owasp_zap_core_values.alert_ref_id')
        ->select(DB::raw("
        SUM(CASE WHEN owasp_zap_core_values.health = 'H1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h1_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h1_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h1_l,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H2' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h2_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H2' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h2_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H2' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h2_l,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H3' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h3_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H3' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h3_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H3' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h3_l,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H4' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h4_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H4' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h4_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H4' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h4_l,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H5' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h5_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H5' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h5_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H5' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h5_l,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H6' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as h6_h,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H6' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as h6_m,
        SUM(CASE WHEN owasp_zap_core_values.health = 'H6' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as h6_l
    "))
        ->where('scan_id', $scan_id)
        ->first();

    // Calculate log10 for all "H" counts
    $log_h1_h = log10($result->h1_h ?: 1);
    $log_h1_m = log10($result->h1_m ?: 1);
    $log_h1_l = log10($result->h1_l ?: 1);
    $log_h2_h = log10($result->h2_h ?: 1);
    $log_h2_m = log10($result->h2_m ?: 1);
    $log_h2_l = log10($result->h2_l ?: 1);
    $log_h3_h = log10($result->h3_h ?: 1);
    $log_h3_m = log10($result->h3_m ?: 1);
    $log_h3_l = log10($result->h3_l ?: 1);
    $log_h4_h = log10($result->h4_h ?: 1);
    $log_h4_m = log10($result->h4_m ?: 1);
    $log_h4_l = log10($result->h4_l ?: 1);
    $log_h5_h = log10($result->h5_h ?: 1);
    $log_h5_m = log10($result->h5_m ?: 1);
    $log_h5_l = log10($result->h5_l ?: 1);
    $log_h6_h = log10($result->h6_h ?: 1);
    $log_h6_m = log10($result->h6_m ?: 1);
    $log_h6_l = log10($result->h6_l ?: 1);

    // Calculate the weighted score
    $weighted_h1_h = $log_h1_h * 0.7 + 3;
    $weighted_h1_m = $log_h1_m * 0.3;
    $weighted_h1_l = $log_h1_l * 0.05;
    $weighted_h2_h = $log_h2_h * 0.7 + 3;
    $weighted_h2_m = $log_h2_m * 0.3;
    $weighted_h2_l = $log_h2_l * 0.05;
    $weighted_h3_h = $log_h3_h * 5;
    $weighted_h3_m = $log_h3_m * 2;
    $weighted_h3_l = $log_h3_l * 0.5;
    $weighted_h4_h = $log_h4_h * 5;
    $weighted_h4_m = $log_h4_m * 2;
    $weighted_h4_l = $log_h4_l * 0.5;
    $weighted_h5_h = $log_h5_h * 5;
    $weighted_h5_m = $log_h5_m * 2;
    $weighted_h5_l = $log_h5_l * 0.5;
    $weighted_h6_h = $log_h6_h * 5;
    $weighted_h6_m = $log_h6_m * 2;
    $weighted_h6_l = $log_h6_l * 0.5;

//    Health Score
    $h1 = 9 - $weighted_h1_h - $weighted_h1_m - $weighted_h1_l;
    $h2 = 9 - $weighted_h2_h - $weighted_h2_m - $weighted_h2_l;
    $h3 = 9 - $weighted_h3_h - $weighted_h3_m - $weighted_h3_l;
    $h4 = 9 - $weighted_h4_h - $weighted_h4_m - $weighted_h4_l;
    $h5 = 9 - $weighted_h5_h - $weighted_h5_m - $weighted_h5_l;
    $h6 = 9 - $weighted_h6_h - $weighted_h6_m - $weighted_h6_l;

    $health_classes = \App\Models\HealthClass::query()->pluck('class', 'h')->toArray();
    return [
        'h1' => [
            'score' => number_format($h1, 2),
            'class' => $health_classes['H1']
        ],
        'h2' => [
            'score' => number_format($h2, 2),
            'class' => $health_classes['H2']
        ],
        'h3' => [
            'score' => number_format($h3, 2),
            'class' => $health_classes['H3']
        ],
        'h4' => [
            'score' => number_format($h4, 2),
            'class' => $health_classes['H4']
        ],
        'h5' => [
            'score' => number_format($h5, 2),
            'class' => $health_classes['H5']
        ],
        'h6' => [
            'score' => number_format($h6, 2),
            'class' => $health_classes['H6']
        ],
    ];
}


function generateAttackData($scan_id)
{
    $result = DB::table('scan_alerts')
        ->join('owasp_zap_core_values', 'scan_alerts.alertRef', '=', 'owasp_zap_core_values.alert_ref_id')
        ->select(DB::raw("
        SUM(CASE WHEN owasp_zap_core_values.s1 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s1_h,
        SUM(CASE WHEN owasp_zap_core_values.s1 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s1_m,
        SUM(CASE WHEN owasp_zap_core_values.s1 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s1_l,
        SUM(CASE WHEN owasp_zap_core_values.s2 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s2_h,
        SUM(CASE WHEN owasp_zap_core_values.s2 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s2_m,
        SUM(CASE WHEN owasp_zap_core_values.s2 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s2_l,
        SUM(CASE WHEN owasp_zap_core_values.s3 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s3_h,
        SUM(CASE WHEN owasp_zap_core_values.s3 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s3_m,
        SUM(CASE WHEN owasp_zap_core_values.s3 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s3_l,
        SUM(CASE WHEN owasp_zap_core_values.s4 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s4_h,
        SUM(CASE WHEN owasp_zap_core_values.s4 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s4_m,
        SUM(CASE WHEN owasp_zap_core_values.s4 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s4_l,
        SUM(CASE WHEN owasp_zap_core_values.s5 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s5_h,
        SUM(CASE WHEN owasp_zap_core_values.s5 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s5_m,
        SUM(CASE WHEN owasp_zap_core_values.s5 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s5_l,
        SUM(CASE WHEN owasp_zap_core_values.s6 = '1' AND owasp_zap_core_values.hml = 'H' THEN 1 ELSE 0 END) as s6_h,
        SUM(CASE WHEN owasp_zap_core_values.s6 = '1' AND owasp_zap_core_values.hml = 'M' THEN 1 ELSE 0 END) as s6_m,
        SUM(CASE WHEN owasp_zap_core_values.s6 = '1' AND owasp_zap_core_values.hml = 'L' THEN 1 ELSE 0 END) as s6_l
    "))
        ->where('scan_id', $scan_id)
        ->first();

    $s1 = ($result->s1_h * 12) + ($result->s1_m * 2) + ($result->s1_l * 0.5);
    $s2 = ($result->s2_h * 12) + ($result->s2_m * 2) + ($result->s2_l * 0.5);
    $s3 = ($result->s3_h * 12) + ($result->s3_m * 2) + ($result->s3_l * 0.5);
    $s4 = ($result->s4_h * 12) + ($result->s4_m * 2) + ($result->s4_l * 0.5);
    $s5 = ($result->s5_h * 12) + ($result->s5_m * 2) + ($result->s5_l * 0.5);
    $s6 = ($result->s6_h * 12) + ($result->s6_m * 2) + ($result->s6_l * 0.5);

    $attack_classes = \App\Models\AttackClass::query()->pluck('class', 's')->toArray();
    return [
        's1' => [
            'score' => $s1,
            'likelihood' => getThreshold($s1)[0],
            'color' => getThreshold($s1)[1],
            'class' => $attack_classes['S1'] ?? '',
        ],
        's2' => [
            'score' => $s2,
            'likelihood' => getThreshold($s2)[0],
            'color' => getThreshold($s2)[1],
            'class' => $attack_classes['S2'] ?? '',
        ],
        's3' => [
            'score' => $s3,
            'likelihood' => getThreshold($s3)[0],
            'color' => getThreshold($s3)[1],
            'class' => $attack_classes['S3'] ?? '',
        ],
        's4' => [
            'score' => $s4,
            'likelihood' => getThreshold($s4)[0],
            'color' => getThreshold($s4)[1],
            'class' => $attack_classes['S4'] ?? '',
        ],
        's5' => [
            'score' => $s5,
            'likelihood' => getThreshold($s5)[0],
            'color' => getThreshold($s5)[1],
            'class' => $attack_classes['S5'] ?? '',
        ],
        's6' => [
            'score' => $s6,
            'likelihood' => getThreshold($s6)[0],
            'color' => getThreshold($s6)[1],
            'class' => $attack_classes['S6'] ?? '',
        ],
    ];


}

function getThreshold($score)
{
    if ($score > 2000) {
        return ['Probable', '#ff0000'];
    } elseif ($score > 250) {
        return ['Possible', '#ffd65a'];
    } else {
        return ['Unlikely', '#19b861'];
    }
}

function generateHistoricalScore($scan_id)
{
    // If there are no scan alerts for this scan, return a default score of 0.
    if (!DB::table('scan_alerts')->where('scan_id', $scan_id)->exists()) {
        return number_format(0, 2);
    }

    // Retrieve detailed health and attack data for the given scan.
    $healthData = generateHealthData($scan_id);
    $attackData = generateAttackData($scan_id);

    // Calculate average health score.
    // Each health score from generateHealthData() is on a scale where 9 is ideal.
    $healthSum = 0;
    $healthCount = count($healthData);
    foreach ($healthData as $health) {
        $healthSum += floatval($health['score']);
    }
    // If there are no health values, default to 0.
    $avgHealth = $healthCount > 0 ? $healthSum / $healthCount : 0;

    // Calculate average attack score.
    // Higher attack scores represent higher risk.
    $attackSum = 0;
    $attackCount = count($attackData);
    foreach ($attackData as $attack) {
        $attackSum += $attack['score'];
    }
    $avgAttack = $attackCount > 0 ? $attackSum / $attackCount : 0;

    // Normalize the attack score.
    // We assume that an average attack score of 2000 corresponds to the worst-case scenario on a 0-9 scale.
    $normalizedAttack = min($avgAttack / 2000, 1) * 9;

    // Compute a composite score.
    // (9 - normalizedAttack) represents the "attack safety" (a higher value is better),
    // so by averaging it with the average health score, we get a balanced overall score.
    $overallScore = ($avgHealth + (9 - $normalizedAttack)) / 2;

    // Ensure the score is not negative.
    $overallScore = max(0, $overallScore);

    // Return the composite score formatted to two decimal places.
    return number_format($overallScore, 2);
}
