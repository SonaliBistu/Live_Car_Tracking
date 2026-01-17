<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Help & Support</title>

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
    background:radial-gradient(circle at top,#141b2d,#05070d);
    color:#fff;
}

/* ===== LAYOUT ===== */
.container{
    max-width:1000px;
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
    background:linear-gradient(90deg,#00f2fe,#4facfe);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

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
    padding:30px;
    box-shadow:
        0 15px 40px rgba(0,0,0,.6),
        inset 0 0 0 1px rgba(255,255,255,.08);
}

/* ===== SECTION ===== */
.section{
    margin-bottom:30px;
}

.section h2{
    color:var(--neon);
    margin-bottom:10px;
}

/* ===== FAQ ===== */
.faq{
    margin-top:20px;
}

.faq-item{
    background:rgba(255,255,255,0.06);
    border-radius:16px;
    margin-bottom:15px;
    overflow:hidden;
    cursor:pointer;
    transition:.3s;
}

.faq-item:hover{
    box-shadow:0 0 20px rgba(0,242,254,.25);
}

.faq-question{
    padding:18px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:500;
}

.faq-question span{
    transition:.3s;
}

.faq-answer{
    max-height:0;
    overflow:hidden;
    padding:0 20px;
    opacity:0;
    transition:.4s ease;
    font-size:14px;
    line-height:1.6;
}

.faq-item.active .faq-answer{
    max-height:200px;
    padding:0 20px 18px;
    opacity:1;
}

.faq-item.active .faq-question span{
    transform:rotate(180deg);
}

/* ===== CONTACT ===== */
.contact{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.contact-box{
    background:linear-gradient(135deg,#00f2fe,#4facfe);
    color:#000;
    padding:20px;
    border-radius:18px;
    text-align:center;
}

.contact-box b{
    display:block;
    margin-bottom:6px;
}

/* ===== FOOTER ===== */
.footer{
    text-align:center;
    opacity:.6;
    font-size:13px;
    margin-top:30px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:600px){
    .faq-question{
        flex-direction:column;
        align-items:flex-start;
        gap:6px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>❓ Help & Support</h1>
        <a class="back" href="dashboard.php">Dashboard</a>
    </div>

    <div class="card">

        <!-- INTRO -->
        <div class="section">
            <h2>Getting Started</h2>
            <p>
                This GPS Vehicle Tracking System helps you monitor your vehicles in
                real time, receive alerts, and analyze trips safely and efficiently.
            </p>
        </div>

        <!-- FAQ -->
        <div class="section">
            <h2>Frequently Asked Questions</h2>

            <div class="faq">

                <div class="faq-item">
                    <div class="faq-question">
                        How does live tracking work?
                        <span>▼</span>
                    </div>
                    <div class="faq-answer">
                        Live tracking uses GPS data from the vehicle device and displays
                        the latest location on the dashboard map.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Why is my vehicle showing offline?
                        <span>▼</span>
                    </div>
                    <div class="faq-answer">
                        This usually happens due to GPS signal loss, power issues,
                        or ignition being switched off.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        How are alerts generated?
                        <span>▼</span>
                    </div>
                    <div class="faq-answer">
                        Alerts are automatically generated from GPS data such as
                        speed limit, fuel level, battery voltage, and engine status.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Can I download reports?
                        <span>▼</span>
                    </div>
                    <div class="faq-answer">
                        Yes, reports can be generated for trips, alerts, and vehicle
                        performance (PDF/Excel – if enabled).
                    </div>
                </div>

            </div>
        </div>

        <!-- CONTACT -->
        <div class="section">
            <h2>Contact Support</h2>

            <div class="contact">
                <div class="contact-box">
                    <b>Email</b>
                    support@cartrack.com
                </div>

                <div class="contact-box">
                    <b>Phone</b>
                    +91-XXXXXXXXXX
                </div>

                <div class="contact-box">
                    <b>Working Hours</b>
                    Mon – Sat (9 AM – 6 PM)
                </div>
            </div>
        </div>

        <div class="footer">
            © <?= date("Y"); ?> CarTrack System. All rights reserved.
        </div>

    </div>
</div>

<script>
document.querySelectorAll('.faq-item').forEach(item=>{
    item.addEventListener('click',()=>{
        item.classList.toggle('active');
    });
});
</script>

</body>
</html>
