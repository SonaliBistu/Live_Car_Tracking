<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","car");
if ($conn->connect_error) die("DB Error");

/* ================= FETCH LATEST GPS ================= */
$sql = "
SELECT g.*
FROM gps_logs g
INNER JOIN (
    SELECT vehicle_id, MAX(`timestamp`) AS ts
    FROM gps_logs
    GROUP BY vehicle_id
) latest
ON g.vehicle_id = latest.vehicle_id
AND g.`timestamp` = latest.ts
";

$result = $conn->query($sql);
if (!$result) die($conn->error);

/* ================= ALERT FUNCTION ================= */
function saveAlert($conn,$vehicle,$message,$type){
    $chk=$conn->prepare("
        SELECT id FROM alerts
        WHERE vehicle_id=? AND message=?
        AND created_at > NOW() - INTERVAL 5 MINUTE
    ");
    $chk->bind_param("ss",$vehicle,$message);
    $chk->execute();
    $chk->store_result();

    if($chk->num_rows==0){
        $ins=$conn->prepare("
            INSERT INTO alerts(vehicle_id,message,type)
            VALUES(?,?,?)
        ");
        $ins->bind_param("sss",$vehicle,$message,$type);
        $ins->execute();
    }
}

/* ================= GENERATE ALERTS ================= */
while($r=$result->fetch_assoc()){
    if($r['speed']>90)
        saveAlert($conn,$r['vehicle_id'],"Speed exceeded {$r['speed']} km/h","warning");
    if($r['engine_status']==0)
        saveAlert($conn,$r['vehicle_id'],"Engine turned OFF","offline");
    if($r['ignition']==0)
        saveAlert($conn,$r['vehicle_id'],"Ignition switched OFF","offline");
    if($r['battery_voltage']!==null && $r['battery_voltage']<11.5)
        saveAlert($conn,$r['vehicle_id'],"Low battery ({$r['battery_voltage']}V)","warning");
    if($r['fuel_level']!==null && $r['fuel_level']<15)
        saveAlert($conn,$r['vehicle_id'],"Low fuel ({$r['fuel_level']}%)","warning");
}

/* ================= FETCH ALERTS ================= */
$alerts=$conn->query("SELECT * FROM alerts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vehicle Alerts</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

:root{
    --neon:#00f2fe;
    --bg:#0b0f1a;
}

*{box-sizing:border-box}

body{
    margin:0;
    font-family:Poppins,sans-serif;
    background:
        radial-gradient(circle at top,#141b2d,#05070d);
    color:#fff;
}

/* ===== LAYOUT ===== */
.container{
    max-width:1100px;
    margin:40px auto;
    padding:0 20px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header h1{
    font-weight:600;
    letter-spacing:1px;
    background:linear-gradient(90deg,#00f2fe,#4facfe);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

/* ===== BUTTON ===== */
.back{
    padding:10px 18px;
    border-radius:30px;
    text-decoration:none;
    color:#000;
    background:linear-gradient(135deg,#00f2fe,#4facfe);
    font-size:14px;
    transition:.3s;
}
.back:hover{
    transform:scale(1.05);
    box-shadow:0 0 25px rgba(0,242,254,.6);
}

/* ===== CARD ===== */
.card{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(18px);
    border-radius:22px;
    padding:25px;
    box-shadow:
        0 15px 40px rgba(0,0,0,.6),
        inset 0 0 0 1px rgba(255,255,255,.08);
}

/* ===== ALERT ITEM ===== */
.alert{
    position:relative;
    padding:18px 22px;
    border-radius:18px;
    margin-bottom:18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    overflow:hidden;
    cursor:pointer;
    transition:.35s ease;
}

.alert::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,.25),transparent);
    opacity:0;
    transition:.4s;
}

.alert:hover::before{opacity:1}
.alert:hover{transform:translateY(-4px)}

.alert b{font-weight:600}

/* ===== TYPES ===== */
.alert.warning{
    background:linear-gradient(135deg,#ffb347,#ffcc33);
    color:#000;
}
.alert.offline{
    background:linear-gradient(135deg,#ff416c,#ff4b2b);
}
.alert.online{
    background:linear-gradient(135deg,#00ffcc,#00ccff);
    color:#000;
}

.alert span{
    font-size:12px;
    opacity:.85;
    white-space:nowrap;
}

/* ===== GLOW ===== */
.alert::after{
    content:"";
    position:absolute;
    inset:-2px;
    border-radius:20px;
    z-index:-1;
    filter:blur(18px);
    opacity:.6;
}
.alert.warning::after{background:#ffd200}
.alert.offline::after{background:#ff416c}
.alert.online::after{background:#00f2fe}

/* ===== EMPTY ===== */
.empty{
    text-align:center;
    opacity:.6;
    padding:40px 0;
}

/* ===== RESPONSIVE ===== */
@media(max-width:700px){
    .alert{
        flex-direction:column;
        align-items:flex-start;
        gap:8px;
    }
}
</style>
</head>

<body>

<div class="container">
    <div class="header">
        <h1>🚨 Vehicle Alerts</h1>
        <a class="back" href="dashboard.php">Dashboard</a>
    </div>

    <div class="card">
        <?php if($alerts->num_rows>0): ?>
            <?php while($a=$alerts->fetch_assoc()): ?>
                <div class="alert <?= $a['type']; ?>">
                    <div>
                        <b><?= htmlspecialchars($a['vehicle_id']); ?></b>
                        — <?= htmlspecialchars($a['message']); ?>
                    </div>
                    <span><?= date("d M Y • h:i A",strtotime($a['created_at'])); ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">No alerts available</div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
